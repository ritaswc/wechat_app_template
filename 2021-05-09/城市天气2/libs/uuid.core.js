/*
 Version: v3.3.0
 The MIT License: Copyright (c) 2010-2016 LiosK.
*/
var UUID;UUID=function(g){"use strict";function f(){}function b(c){return 0>c?NaN:30>=c?0|Math.random()*(1<<c):53>=c?(0|1073741824*Math.random())+1073741824*(0|Math.random()*(1<<c-30)):NaN}function a(c,b){for(var a=c.toString(16),d=b-a.length,e="0";0<d;d>>>=1,e+=e)d&1&&(a=e+a);return a}f.generate=function(){return a(b(32),8)+"-"+a(b(16),4)+"-"+a(16384|b(12),4)+"-"+a(32768|b(14),4)+"-"+a(b(48),12)};f.overwrittenUUID=g;return f}(UUID);
