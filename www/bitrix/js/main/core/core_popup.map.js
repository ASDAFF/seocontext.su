{"version":3,"file":"core_popup.min.js","sources":["core_popup.js"],"names":["window","BX","PopupWindowManager","_popups","_currentPopup","create","uniquePopupId","bindElement","params","index","this","_getPopupIndex","popupWindow","PopupWindow","addCustomEvent","delegate","onPopupShow","onPopupClose","onPopupDestroy","push","close","util","deleteFromArray","getCurrentPopup","isPopupExists","i","length","onCustomEvent","zIndex","parseInt","isNaN","buttons","type","isArray","offsetTop","getOption","offsetLeft","firstShow","bordersWidth","bindElementPos","closeIcon","angle","overlay","titleBar","bindOptions","isAutoHideBinded","closeByEsc","isCloseByEscBinded","parentPopup","appendContainer","contentContainer","fullscreenStatus","popupContainer","document","body","dragged","dragPageX","dragPageY","events","eventName","createElement","adjust","props","id","style","getZindex","position","display","top","left","darkMode","addClass","tableClassName","lightShadow","className","isNotEmptyString","innerHTML","join","appendChild","href","click","proxy","_onCloseIconClick","browser","IsIE","attrs","hidefocus","buttonsContainer","buttonsHr","setAngle","setOverlay","setOffset","setBindElement","setTitleBar","setContent","content","setButtons","bindOnResize","bind","_onResizeWindow","prototype","isElementNode","cleanNode","parentNode","removeChild","isString","remove","newButtons","button","is_subclass_of","PopupWindowButton","render","children","isDomNode","isNumber","clientX","clientY","fixEventPageXY","pageX","pageY","bottom","getBindElementPos","pos","windowSize","GetWindowInnerSize","windowScroll","GetWindowScrollPos","popupWidth","offsetWidth","popupHeight","offsetHeight","forceTop","innerWidth","scrollLeft","innerHeight","scrollTop","angleMinLeft","defaultOffset","offset","angleLeftOffset","defaultOptions","element","Math","max","in_array","removeClass","minOffset","maxOffset","min","marginLeft","isTopAngle","isBottomAngle","isTopOrBottomAngle","getAngleHeight","draggable","cursor","_startDrag","setClosingByEsc","enable","_onKeyUp","unbind","adjustOverlayZindex","resizeOverlay","opacity","IsIE9","filter","parseFloat","toPrecision","backgroundColor","removeOverlay","hideOverlay","showOverlay","width","height","GetWindowScrollSize","scrollHeight","documentElement","clientHeight","scrollWidth","show","adjustPosition","autoHide","setTimeout","isShown","cancelBubble","event","stopPropagation","getEventButton","MSLEFT","_close","PreventDefault","keyCode","_checkEscPressed","destroy","unbindAll","_moveDrag","_stopDrag","enterFullScreen","cancelFullScreen","mozCancelFullScreen","webkitCancelFullScreen","IsChrome","IsSafari","webkitRequestFullScreen","ALLOW_KEYBOARD_INPUT","fullscreenBind","eventFullScreen","IsFirefox","mozRequestFullScreen","forceBindPosition","angleTopOffset","forceLeft","bindLeft","move","offsetX","offsetY","restrict","scrollSize","floatWidth","floatHeight","setCapture","ondrag","False","onselectstart","MozUserSelect","releaseCapture","options","positionTopXOffset","popupZindex","popupOverlayZindex","angleMaxLeft","angleMinRight","angleMaxRight","angleMinBottom","angleMaxBottom","angleMinTop","angleMaxTop","setOptions","option","defaultValue","text","contextEvents","nameNode","buttonNode","setName","name","setClassName","PopupWindowButtonLink","superclass","constructor","apply","arguments","extend","PopupMenu","Data","currentItem","menuItems","PopupMenuWindow","getCurrentMenu","getMenuById","menu","itemsContainer","__addMenuItem","__createPopup","__createItem","item","layout","hr","delimiter","html","title","onclick","target","isFunction","onItemClick","obj","domItems","hasOwnProperty","call","addMenuItem","menuItem","refItemId","refItem","getMenuItem","insertBefore","getMenuItemPosition","insertIntoArray","removeMenuItem","itemId","BXInputPopup","round","random","handler","values","pInput","input","bValues","openTitle","noMRclassName","emptyClassName","_this","curInd","onfocus","e","value","ShowPopup","onblur","bShowed","ClosePopup","OnChange","oPopup","pRow","pWnd","l","NAME","mouseover","mouseout","ind","substr","DESCRIPTION","CLASS_NAME","URL","select","bClosePopup","val","Set","bOnChange","Get","ID","GetIndex","Deactivate","bDeactivate","oEC","bUseMR","disabled","_escCallbackIndex","_escCallback","callback","defer"],"mappings":"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"}