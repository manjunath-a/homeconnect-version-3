function megnorShowMenuPopup(objMenu, popupId)
{
    if (typeof megnorCustommenuTimerHide[popupId] != 'undefined') clearTimeout(megnorCustommenuTimerHide[popupId]);
    objMenu = $(objMenu.id); var popup = $(popupId); if (!popup) return;
    megnorCustommenuTimerShow[popupId] = setTimeout(function() {
        popup.style.display = 'block';
        objMenu.addClassName('active');
        var popupWidth = CUSTOMMENU_POPUP_WIDTH;
        if (!popupWidth) popupWidth = popup.getWidth();
        var pos = megnorPopupPos(objMenu, popupWidth);
         
        popup.style.left = pos.left + 'px';
        megnorSetPopupZIndex(popup);
        if (CUSTOMMENU_POPUP_WIDTH)
            popup.style.width = CUSTOMMENU_POPUP_WIDTH + 'px';
        // --- Static Block width ---
        var block2 = $(popupId).select('div.block2');
        if (typeof block2[0] != 'undefined') {
            var wStart = block2[0].id.indexOf('_w');
            if (wStart > -1) {
                var w = block2[0].id.substr(wStart+2);
            } else {
                var w = 0;
                $(popupId).select('div.block1 div.column').each(function(item) {
                    w += $(item).getWidth();
                });
            }
            //console.log(w);
            //if (w) block2[0].style.width = w + 'px';
        }
    }, CUSTOMMENU_POPUP_DELAY_BEFORE_DISPLAYING);
}

function megnorHideMenuPopup(element, event, popupId, menuId)
{
    if (typeof megnorCustommenuTimerShow[popupId] != 'undefined') clearTimeout(megnorCustommenuTimerShow[popupId]);
    element = $(element.id); var popup = $(popupId); if (!popup) return;
    var current_mouse_target = null;
    if (event.toElement) {
        current_mouse_target = event.toElement;
    } else if (event.relatedTarget) {
        current_mouse_target = event.relatedTarget;
    }
    megnorCustommenuTimerHide[popupId] = setTimeout(function() {
        if (!megnorIsChildOf(element, current_mouse_target) && element != current_mouse_target) {
            if (!megnorIsChildOf(popup, current_mouse_target) && popup != current_mouse_target) {
                popup.style.display = 'none';
                $(menuId).removeClassName('active');
            }
        }
    }, CUSTOMMENU_POPUP_DELAY_BEFORE_HIDING);
}

function megnorPopupOver(element, event, popupId, menuId)
{
    if (typeof megnorCustommenuTimerHide[popupId] != 'undefined') clearTimeout(megnorCustommenuTimerHide[popupId]);
}

function megnorPopupPos(objMenu, w)
{
    var pos = objMenu.cumulativeOffset();
    var wraper = $('advancedmenu');
    var posWraper = wraper.cumulativeOffset();
    var wWraper = wraper.getWidth() - CUSTOMMENU_POPUP_RIGHT_OFFSET_MIN;
     
    var xLeft = pos.left - posWraper.left;
    if ((xLeft + w) > wWraper) xLeft = wWraper - w;
    return {'left': xLeft};
}

function megnorIsChildOf(parent, child)
{
    if (child != null) {
        while (child.parentNode) {
            if ((child = child.parentNode) == parent) {
                return true;
            }
        }
    }
    return false;
}

function megnorSetPopupZIndex(popup)
{
    $$('.megnor-advanced-menu-popup').each(function(item){
       item.style.zIndex = '9999';
    });
    popup.style.zIndex = '10000';
}
