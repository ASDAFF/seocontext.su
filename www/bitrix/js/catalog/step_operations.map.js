{"version":3,"file":"step_operations.min.js","sources":["step_operations.js"],"names":["BX","namespace","Catalog","StepOperations","classDescription","params","this","errorCode","url","stepOptions","ajaxSessionID","maxExecutionTime","maxOperationCounter","finish","currentState","counter","operationCounter","errorCounter","lastID","ajaxParams","visual","startBtnID","stopBtnID","resultContID","errorContID","errorDivID","timeFieldID","buttons","start","stop","content","result","errors","errorsFrame","timeField","undefined","type","isNotEmptyString","addError","options","ready","proxy","init","prototype","bind","startOperation","stopOperation","changeMaxTime","extendAjaxParams","initResultDom","nextStep","key","hasOwnProperty","sessid","bitrix_sessid","lang","message","showWait","ajax","loadJSON","nextStepResult","closeWait","parseInt","isNaN","showResult","showErrors","finishOperation","checkOperation","adjust","html","style","display","errorList","innerHTML","disabled","maxTime","value","code","CatalogReindex","catalogs","catalogIndex","catalogSelect","report","catalogContent","messages","catalogErrorTitle","superclass","constructor","apply","arguments","catalogSelectID","reportID","extend","getIblockList","selectedIndex","getIblock","iblock","getIblockListResult","isArray","length","catalogReindex","clearOldReports","createReindexReport","initStep","iblockId","container","ID","appendChild","create","props","id","prefix","COUNT","clearTagCache","iblockList","i","get","clearTags","cleanNode"],"mappings":"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"}