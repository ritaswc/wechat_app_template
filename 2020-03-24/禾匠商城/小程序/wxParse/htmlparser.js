var startTag = /^<([-A-Za-z0-9_]+)((?:\s+[a-zA-Z_:][-a-zA-Z0-9_:.]*(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/, endTag = /^<\/([-A-Za-z0-9_]+)[^>]*>/, attr = /([a-zA-Z_:][-a-zA-Z0-9_:.]*)(?:\s*=\s*(?:(?:"((?:\\.|[^"])*)")|(?:'((?:\\.|[^'])*)')|([^>\s]+)))?/g, empty = makeMap("area,base,basefont,br,col,frame,hr,img,input,link,meta,param,embed,command,keygen,source,track,wbr"), block = makeMap("a,address,code,article,applet,aside,audio,blockquote,button,canvas,center,dd,del,dir,div,dl,dt,fieldset,figcaption,figure,footer,form,frameset,h1,h2,h3,h4,h5,h6,header,hgroup,hr,iframe,ins,isindex,li,map,menu,noframes,noscript,object,ol,output,p,pre,section,script,table,tbody,td,tfoot,th,thead,tr,ul,video"), inline = makeMap("abbr,acronym,applet,b,basefont,bdo,big,br,button,cite,del,dfn,em,font,i,iframe,img,input,ins,kbd,label,map,object,q,s,samp,script,select,small,span,strike,strong,sub,sup,textarea,tt,u,var"), closeSelf = makeMap("colgroup,dd,dt,li,options,p,td,tfoot,th,thead,tr"), fillAttrs = makeMap("checked,compact,declare,defer,disabled,ismap,multiple,nohref,noresize,noshade,nowrap,readonly,selected"), special = makeMap("wxxxcode-style,script,style,view,scroll-view,block");

function HTMLParser(e, n) {
    var t, a, r, i = [], s = e;
    for (i.last = function() {
        return this[this.length - 1];
    }; e; ) {
        if (a = !0, i.last() && special[i.last()]) e = e.replace(new RegExp("([\\s\\S]*?)</" + i.last() + "[^>]*>"), function(e, t) {
            return t = t.replace(/<!--([\s\S]*?)-->|<!\[CDATA\[([\s\S]*?)]]>/g, "$1$2"), n.chars && n.chars(t), 
            "";
        }), c("", i.last()); else if (0 == e.indexOf("\x3c!--") ? 0 <= (t = e.indexOf("--\x3e")) && (n.comment && n.comment(e.substring(4, t)), 
        e = e.substring(t + 3), a = !1) : 0 == e.indexOf("</") ? (r = e.match(endTag)) && (e = e.substring(r[0].length), 
        r[0].replace(endTag, c), a = !1) : 0 == e.indexOf("<") && (r = e.match(startTag)) && (e = e.substring(r[0].length), 
        r[0].replace(startTag, o), a = !1), a) {
            t = e.indexOf("<");
            for (var l = ""; 0 === t; ) l += "<", t = (e = e.substring(1)).indexOf("<");
            l += t < 0 ? e : e.substring(0, t), e = t < 0 ? "" : e.substring(t), n.chars && n.chars(l);
        }
        if (e == s) throw "Parse Error: " + e;
        s = e;
    }
    function o(e, t, a, r) {
        if (t = t.toLowerCase(), block[t]) for (;i.last() && inline[i.last()]; ) c("", i.last());
        if (closeSelf[t] && i.last() == t && c("", t), (r = empty[t] || !!r) || i.push(t), 
        n.start) {
            var s = [];
            a.replace(attr, function(e, t) {
                var a = arguments[2] ? arguments[2] : arguments[3] ? arguments[3] : arguments[4] ? arguments[4] : fillAttrs[t] ? t : "";
                s.push({
                    name: t,
                    value: a,
                    escaped: a.replace(/(^|[^\\])"/g, '$1\\"')
                });
            }), n.start && n.start(t, s, r);
        }
    }
    function c(e, t) {
        if (t) {
            t = t.toLowerCase();
            for (a = i.length - 1; 0 <= a && i[a] != t; a--) ;
        } else var a = 0;
        if (0 <= a) {
            for (var r = i.length - 1; a <= r; r--) n.end && n.end(i[r]);
            i.length = a;
        }
    }
    c();
}

function makeMap(e) {
    for (var t = {}, a = e.split(","), r = 0; r < a.length; r++) t[a[r]] = !0;
    return t;
}

module.exports = HTMLParser;