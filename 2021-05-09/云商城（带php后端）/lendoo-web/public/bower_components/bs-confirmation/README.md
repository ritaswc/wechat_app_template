# bootstrap-confirmation v1.0.5

This is a fork of ethaizone's [original code](https://github.com/ethaizone/Bootstrap-Confirmation)

with some help from [jibe914](https://github.com/jibe914/Bootstrap-Confirmation)
and [MisatoTremor](https://github.com/MisatoTremor/bootstrap-confirmation)

Confirmation plugin compatible with Twitter Bootstrap 3 extending Popover

## Usage

Create your `button or link` with the `data-toggle="confirmation"`.

    <a href="http://google.com" class="btn" data-toggle="confirmation">Confirmation</a>

Enable plugin via JavaScript:

    $('[data-toggle="confirmation"]').confirmation();

Or if you want to assing to one element

    <a href="http://google.com" class="btn confirmation">Confirmation</a>
    $('.confirmation').confirmation();

## Options

In addition to the standard bootstrap popover options, you now have access to the following options

+ **btnOkClass**
Set the Ok button class. `default: btn btn-sm btn-danger`

+ **btnOkLabel**
Set the Ok button label. `default: Delete`

+ **btnOkIcon**
Set the Ok button icon. `default: glyphicon glyphicon-ok`

+ **btnCancelClass**
Set the Ok button class. `default: btn btn-sm btn-default`

+ **btnCancelLabel**
Set the Ok button label. `default: Cancel`

+ **btnCancelIcon**
Set the Ok button icon. `default: glyphicon glyphicon-remove`

+ **singleton**
Set true to allow only one confirmation to show at a time. `default: true`

+ **popout**
Set true to hide the confirmation when user clicks outside of it. `default: true`

+ **onShow**
Callback when popup show. `default: function(event, element){}`

+ **onHide**
Callback when popup hide. `default: function(event, element){}`

+ **onConfirm**
Callback for confirm button. `default: function(event, element){}`

+ **onCancel**
Callback for cancel button. `default: function(event, element){}`

## Copyright and license

Copyright (C) 2013 bootstrap-confirmation

Licensed under the MIT license.

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
