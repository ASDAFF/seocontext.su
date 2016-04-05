BX.namespace("BX.Catalog");
BX.Catalog.StepOperations = (function()
{
/** @param {{url:string, options: {}, ajaxParams: {}, visual: {}}} params */
var classDescription = function(params)
{
	this.errorCode = 0;
	this.url = '';
	this.stepOptions = {
		ajaxSessionID: '',
		maxExecutionTime: 0,
		maxOperationCounter: 0
	};
	this.finish = false;
	this.currentState = {
		counter: 0,
		operationCounter: 0,
		errorCounter: 0,
		lastID: 0
	};
	this.ajaxParams = {};
	this.visual = {
		startBtnID: '',
		stopBtnID: '',
		resultContID: '',
		errorContID: '',
		errorDivID: '',
		timeFieldID: ''
	};
	this.buttons = {
		start: null,
		stop: null
	};
	this.content = {
		result: null,
		errors: null,
		errorsFrame: null,
		timeField: null
	};

	if (typeof params === 'object')
	{
		if (params.url === undefined || !BX.type.isNotEmptyString(params.url))
			this.addError(-0x0002);
		else
			this.url = params.url;

		if (params.options === 'undefined' || typeof(params.options) !== 'object')
		{
			this.addError(-0x0004);
		}
		else
		{
			this.stepOptions.ajaxSessionID = params.options.ajaxSessionID;
			this.stepOptions.maxExecutionTime = params.options.maxExecutionTime;
			this.stepOptions.maxOperationCounter = params.options.maxOperationCounter;
			this.currentState.counter = params.options.counter;
		}
		if (!!params.ajaxParams && typeof(params.ajaxParams) === 'object')
			this.ajaxParams = params.ajaxParams;

		if (!!params.visual && typeof(params.visual) === 'object')
			this.visual = params.visual;
	}
	else
	{
		this.addError(-0x0001);
	}

	if (this.errorCode === 0)
		BX.ready(BX.proxy(this.init, this));
};

classDescription.prototype.init = function()
{
	if (this.errorCode < 0)
		return;

	if (this.errorCode === 0)
	{
		if (!!this.visual.startBtnID)
		{
			this.buttons.start = BX(this.visual.startBtnID);
			if (!this.buttons.start)
				this.addError(-0x20000);
		}
		else
		{
			this.addError(-0x10000);
		}
		if (!!this.visual.stopBtnID)
		{
			this.buttons.stop = BX(this.visual.stopBtnID);
			if (!this.buttons.stop)
				this.addError(-0x80000);
		}
		else
		{
			this.addError(-0x40000);
		}
		this.content.timeField = BX(this.visual.timeFieldID);
	}

	if (this.errorCode === 0)
	{
		BX.bind(this.buttons.start, 'click', BX.proxy(this.startOperation, this));
		BX.bind(this.buttons.stop, 'click', BX.proxy(this.stopOperation, this));
		if (!!this.content.timeField)
			BX.bind(this.content.timeField, 'change', BX.proxy(this.changeMaxTime, this));
	}
};

classDescription.prototype.extendAjaxParams = function()
{

};

classDescription.prototype.initResultDom = function()
{
	if (this.content.result === null)
	{
		this.content.result = BX(this.visual.resultContID);
		this.content.errorsFrame = BX(this.visual.errorDivID);
		this.content.errors = BX(this.visual.errorContID);
	}
};

classDescription.prototype.nextStep = function()
{
	var key;

	for (key in this.stepOptions)
	{
		if (this.stepOptions.hasOwnProperty(key))
			this.ajaxParams[key] = this.stepOptions[key];
	}
	for (key in this.currentState)
	{
		if (this.currentState.hasOwnProperty(key))
			this.ajaxParams[key] = this.currentState[key];
	}
	this.ajaxParams.sessid = BX.bitrix_sessid();
	this.ajaxParams.lang = BX.message('LANGUAGE_ID');
	this.extendAjaxParams();
	BX.showWait();
	BX.ajax.loadJSON(
		this.url,
		this.ajaxParams,
		BX.proxy(this.nextStepResult, this)
	);
};

classDescription.prototype.nextStepResult = function(result)
{
	BX.closeWait();
	if (typeof result === 'object')
	{
		this.initResultDom();
		this.currentState.lastID = result.lastID;
		this.stepOptions.maxOperationCounter = result.maxOperationCounter;

		this.currentState.operationCounter = parseInt(result.operationCounter, 10);
		if (isNaN(this.currentState.operationCounter))
			this.currentState.operationCounter = 0;

		this.showResult(result.message);

		this.currentState.errorCounter = parseInt(result.errorCounter, 10);
		if (isNaN(this.currentState.errorCounter))
			this.currentState.errorCounter = 0;
		if (this.currentState.errorCounter > 0)
			this.showErrors(result.errors);

		if (this.finish)
			this.finishOperation();
		else
			this.checkOperation(result.finishOperation);
	}
};

classDescription.prototype.checkOperation = function(result)
{
	if (!!result)
		this.finishOperation();
	else
		this.nextStep();
};

classDescription.prototype.showResult = function(result)
{
	if (!!this.content.result)
		BX.adjust(this.content.result, { html: result, style: { display: 'block' } });
};

classDescription.prototype.showErrors = function(errorList)
{
	if (!!this.content.errors)
	{
		if (BX.type.isNotEmptyString(errorList))
			this.content.errors.innerHTML = this.content.errors.innerHTML + errorList;
		BX.style(this.content.errorsFrame, 'display', 'block');
	}
};

classDescription.prototype.finishOperation = function()
{
	this.currentState.operationCounter = 0;
	this.currentState.errorCounter = 0;
	this.currentState.lastID = 0;

	this.buttons.start.disabled = false;
	this.buttons.stop.disabled = true;

	this.finish = false;
};

classDescription.prototype.startOperation = function()
{
	if (!this.buttons.start.disabled)
	{
		this.changeMaxTime();
		this.buttons.start.disabled = true;
		this.buttons.stop.disabled = false;
		this.nextStep();
	}
};

classDescription.prototype.stopOperation = function()
{
	if (!this.buttons.stop.disabled)
	{
		this.buttons.start.disabled = false;
		this.buttons.stop.disabled = true;
		this.finish = true;
	}
};

classDescription.prototype.changeMaxTime = function()
{
	var maxTime;

	if (!!this.content.timeField)
	{
		maxTime = parseInt(this.content.timeField.value, 10);
		if (!isNaN(maxTime))
			this.stepOptions.maxExecutionTime = maxTime;
	}
};

classDescription.prototype.addError = function(code)
{
	this.errorCode = this.errorCode || code;
};

return classDescription;
})();

/**
* @extends {BX.Catalog.StepOperations}
*/
BX.Catalog.CatalogReindex = (function()
{
/**
* @constructor
* @extends {BX.Catalog.StepOperations}
*/
var classDescription = function(params)
{
	this.catalogs = [];
	this.catalogIndex = -1;
	this.catalogSelect = null;
	this.report = null;
	this.catalogContent = [];
	this.messages = {
		catalogErrorTitle: ''
	};
	classDescription.superclass.constructor.apply(this, arguments);
	if (typeof(this.visual.catalogSelectID) === 'undefined')
		this.visual.catalogSelectID = '';
	if (typeof(this.visual.reportID) === 'undefined')
		this.visual.reportID = '';
};
BX.extend(classDescription, BX.Catalog.StepOperations);

/**
 * @extends {BX.Catalog.StepOperations.init}
 * @this {BX.Catalog.CatalogReindex}
 */
classDescription.prototype.init = function()
{
	if (this.errorCode === 0)
	{
		if (!!this.visual.catalogSelectID)
		{
			this.catalogSelect = BX(this.visual.catalogSelectID);
			if (!this.catalogSelect)
				this.addError(-0x200000);
		}
		else
		{
			this.addError(-0x100000);
		}
		if (!!this.visual.reportID)
		{
			this.report = BX(this.visual.reportID);
			if (!this.report)
				this.addError(-0x800000);
		}
		else
		{
			this.addError(-0x400000);
		}
	}
	classDescription.superclass.init.apply(this, arguments);
};

classDescription.prototype.getIblockList = function()
{
	if (this.catalogSelect.selectedIndex != -1 && this.catalogSelect.options[this.catalogSelect.selectedIndex].value !== '')
	{
		BX.showWait();
		BX.ajax.loadJSON(
			this.url,
			{
				sessid: BX.bitrix_sessid(),
				getIblock: 'Y',
				iblock: this.catalogSelect.options[this.catalogSelect.selectedIndex].value
			},
			BX.proxy(this.getIblockListResult, this)
		);
	}
};

classDescription.prototype.getIblockListResult = function(result)
{
	BX.closeWait();
	if (BX.type.isArray(result))
	{
		this.catalogs = result;
		if (this.catalogs.length > 0)
		{
			this.changeMaxTime();
			this.buttons.start.disabled = true;
			this.buttons.stop.disabled = false;
			this.catalogIndex = 0;
			this.catalogReindex();
		}
		else
		{
			// TODO:: show global error - need error div and messages in constructor
		}
	}
};

classDescription.prototype.startOperation = function()
{
	if (!this.buttons.start.disabled)
	{
		this.clearOldReports();
		this.getIblockList();
	}
};

classDescription.prototype.catalogReindex = function()
{
	if (this.catalogs.length == 0)
		return;
	if (this.catalogIndex < 0)
		return;
	if (this.catalogIndex >= this.catalogs.length)
		return;
	if (this.finish)
		return;
	this.createReindexReport();
	this.initStep();
	this.nextStep();
};

classDescription.prototype.createReindexReport = function()
{
	var iblockId;

	if (!this.report)
		return;

	if (this.catalogIndex > 0)
		BX.adjust(this.catalogContent[this.catalogIndex-1].container, {style: { display: 'none' }});

	this.catalogContent[this.catalogIndex] = {
		container: null,
		result: null,
		errors: null,
		errorsFrame: null
	};

	iblockId = this.catalogs[this.catalogIndex].ID;

	this.report.appendChild(BX.create(
		'div',
		{
			props: {
				id: this.visual.prefix + iblockId
			},
			html: '<div id="' + this.visual.resultContID + iblockId + '" style="margin:0; width: 100%; display: none;"></div>' +
			'<div id="' + this.visual.errorDivID + iblockId + '" style="margin:0; width: 100%; display: none;">' +
			'<div class="adm-info-message-wrap adm-info-message-red">' +
			'<div class="adm-info-message">' +
			'<div id="' + this.visual.errorContID + iblockId + '"></div>' +
			'<div class="adm-info-message-icon"></div>' +
			'</div></div></div>'
		}
	));
};

classDescription.prototype.initStep = function()
{
	this.currentState.iblockId = this.catalogs[this.catalogIndex].ID;
	this.currentState.counter = this.catalogs[this.catalogIndex].COUNT;
	this.currentState.operationCounter = 0;
	this.currentState.errorCounter = 0;
	this.currentState.lastID = 0;
};

classDescription.prototype.initResultDom = function()
{
	var iblockId;

	if (this.catalogs.length == 0)
		return;
	if (this.catalogIndex < 0)
		return;
	if (this.catalogIndex >= this.catalogs.length)
		return;

	if (this.catalogContent[this.catalogIndex].container === null)
	{
		iblockId = this.catalogs[this.catalogIndex].ID;
		this.catalogContent[this.catalogIndex].container = BX(this.visual.prefix + iblockId);
		this.catalogContent[this.catalogIndex].result = BX(this.visual.resultContID + iblockId);
		this.catalogContent[this.catalogIndex].errors = BX(this.visual.errorContID + iblockId);
		this.catalogContent[this.catalogIndex].errorsFrame = BX(this.visual.errorDivID + iblockId);
	}
};

classDescription.prototype.checkOperation = function(result)
{
	if (!!result)
	{
		this.catalogIndex++;
		if (this.catalogIndex >= this.catalogs.length || this.currentState.errorCounter > 0)
		{
			this.finishOperation();
			if (this.currentState.errorCounter == 0)
				this.clearTagCache();
		}
		else
		{
			this.createReindexReport();
			this.initStep();
			this.nextStep();
		}
	}
	else
	{
		this.nextStep();
	}
};

classDescription.prototype.showResult = function(result)
{
	if (this.catalogs.length == 0)
		return;
	if (this.catalogIndex < 0)
		return;
	if (this.catalogIndex >= this.catalogs.length)
		return;

	if (!!this.catalogContent[this.catalogIndex].container)
	{
		if (!!this.catalogContent[this.catalogIndex].result)
			BX.adjust(this.catalogContent[this.catalogIndex].result, {html: result, style: {display: 'block'}});
		BX.adjust(this.catalogContent[this.catalogIndex].container, { style: {display: 'block'} });
		BX.adjust(this.report, { style: { display: 'block' }});
	}
};

classDescription.prototype.showErrors = function(errorList)
{
	if (this.catalogs.length == 0)
		return;
	if (this.catalogIndex < 0)
		return;
	if (this.catalogIndex >= this.catalogs.length)
		return;

	if (!!this.catalogContent[this.catalogIndex].container)
	{
		if (!!this.catalogContent[this.catalogIndex].errors)
		{
			if (BX.type.isNotEmptyString(errorList))
				this.catalogContent[this.catalogIndex].errors = this.catalogContent[this.catalogIndex].errors.innerHTML + errorList;
			BX.style(this.catalogContent[this.catalogIndex].errorsFrame, 'display', 'block');
		}
	}
};

classDescription.prototype.clearTagCache = function()
{
	var iblockList = [],
		i;

	if (this.catalogs.length > 0)
	{
		for (i = 0; i < this.catalogs.length; i++)
			iblockList[iblockList.length] = this.catalogs[i].ID;

		BX.ajax.get(
			this.url,
			{
				sessid: BX.bitrix_sessid(),
				clearTags: 'Y',
				iblockList: iblockList
			}
		);
	}
};

classDescription.prototype.clearOldReports = function()
{
	var i;

	if (this.catalogContent.length > 0)
	{
		for (i = 0; i < this.catalogContent.length; i++)
		{
			if (!!this.catalogContent[i].container)
			{
				this.catalogContent[i].container = BX.cleanNode(this.catalogContent[i].container, true);
				this.catalogContent[i].result = null;
				this.catalogContent[i].errorsFrame = null;
				this.catalogContent[i].errors = null;
			}
		}
		this.catalogContent.length = 0;
	}
};

return classDescription;
})();