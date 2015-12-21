/*
 *
 * Copyright (c) 2015 Marian Bergerot & Jerome Demonchaux
 *
 */
(function(factory){
    if(typeof define === 'function' && define.amd){
        define(['jquery'], factory);
    }else{
        factory(window.jQuery);
    }
}(function($) {

    $.fn.addrMac = function(config, callback)
    {
        //$('body').append('<input id="keypress'+$(this).attr("id")+'" name="name" value="" type="text">');
        return this.keypress($.fn.addrMac.keypress).keyup($.fn.addrMac.keyup);

    };

    $.fn.addrMac.keypress = function(e)
    {
        // get the key that was pressed
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;

        str = $(this).val();
        console.log(key);

        // allow Ctrl+A
        if((e.ctrlKey && key == 97 /* firefox */) || (e.ctrlKey && key == 65) /* opera */) { return true; }
        if (key==8 /*backspace*/) {	return true	}
        if (key==9 /*tab*/) {	return true	}
        if (str.length == 17 && key!=8) {
            return false
        }else {
            // allow Ctrl+X (cut)
            if((e.ctrlKey && key == 120 /* firefox */) || (e.ctrlKey && key == 88) /* opera */) { return true; }
            // allow Ctrl+C (copy)
            if((e.ctrlKey && key == 99 /* firefox */) || (e.ctrlKey && key == 67) /* opera */) { return true; }
            // allow Ctrl+Z (undo)
            if((e.ctrlKey && key == 122 /* firefox */) || (e.ctrlKey && key == 90) /* opera */) { return true; }
            // allow or deny Ctrl+V (paste), Shift+Ins
            if((e.ctrlKey && key == 118 /* firefox */) || (e.ctrlKey && key == 86) /* opera */ ||
                (e.shiftKey && key == 45)) { return true; }
            if (key>47 && key<58) {
                $('#keypresshex').trigger(e);
                return true
            }if (key>96 && key<103) {
                $('#keypress'+$(this).attr("id")).trigger(e);
                return true
            }if (key>36 && key<41) {
                $('#keypress'+$(this).attr("id")).trigger(e);
                return true
            }else {
                return false
            }
        }

    }

    $.fn.addrMac.keyup = function(e)
    {
        str = $(this).val();
        tabstr = str.match(/[0-9A-Fa-f]{1}/gi)
        if (tabstr != null) {
            str="";
            for (var i = 0; i < tabstr.length && i < 12; i++) {
                str = str + tabstr[i].toUpperCase();
                if (i==1||i==3||i==5||i==7||i==9) {
                    str = str + ":";
                }
            }
            $(this).val(str);
            return
        }
        $(this).val("");
    }
}));