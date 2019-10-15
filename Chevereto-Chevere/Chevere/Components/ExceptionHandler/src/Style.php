<?php

/*
 * This file is part of Chevere.
 *
 * (c) Rodolfo Berrios <rodolfo@chevereto.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Chevere\Components\ExceptionHandler\src;

// FIXME: Code font (inline) must be smaller
/**
 * Stores the styling (CSS) for ExceptionHandler.
 */
class Style
{
    const CSS = 'html{color:#000;font:16px Helvetica,Arial,sans-serif;line-height:1.3;background:#3498db;background:-moz-linear-gradient(top,#3498db 0%,#8e44ad 100%);background:-webkit-linear-gradient(top,#3498db 0%,#8e44ad 100%);background:linear-gradient(to bottom,#3498db 0%,#8e44ad 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#3498db",endColorstr="#8e44ad",GradientType=0)}.body--block{margin:20px}.body--flex{margin:0;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center}.user-select-none{-webkit-touch-callout:none;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}main{background:none;display:block;padding:0;margin:0;border:0;width:470px}.body--block main{margin:0 auto}@media (min-width:768px){main{padding:20px}}.main--stack{width:100%;max-width:900px}.hr{display:block;height:1px;color:transparent;background:hsl(192,15%,84%)}.hr>span{opacity:0;line-height:0}.main--stack hr:last-of-type{margin-bottom:0}.t{font-weight:700;margin-bottom:5px}.t--scream{font-size:2.25em;margin-bottom:0}.t--scream span{font-size:.667em;font-weight:400}.t--scream span::before{white-space:pre;content:"\A"}.t>.hide{display:inline-block}.c code{padding:2px 5px}.c code,.c pre{background:hsl(192,15%,95%);line-height:normal}.c pre.pre--even{background:hsl(192,15%,97%)}.c pre{overflow:auto;word-wrap:break-word;font-size:13px;font-family:Consolas,monospace,sans-serif;display:block;margin:0;padding:10px}main>div{padding:20px;background:#FFF}main>div,main>div> *{word-break:break-word;white-space:normal}@media (min-width:768px){main>div{-webkit-box-shadow:2px 2px 4px 0 rgba(0,0,0,.09);box-shadow:2px 2px 4px 0 rgba(0,0,0,.09);border-radius:2px}}main>div>:first-child{margin-top:0}main>div>:last-child{margin-bottom:0}.note{margin:1em 0}.fine-print{color:#BBB}.hide{width:0;height:0;opacity:0;overflow:hidden}
    .c pre {
        border: 1px solid hsl(192,15%,84%);
        border-bottom: 0; 
        border-top: 0;
    }';
}