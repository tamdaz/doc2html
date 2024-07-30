<?php

namespace Tamdaz\Doc2Html\Enums;

/**
 * Contains all HTML standard tag names.
 */
enum TagType: string
{
    case SPAN_ELEMENT = "span";
    case DIV_ELEMENT = "div";
    case A_ELEMENT = "a";
    case ARTICLE_ELEMENT = "article";
    case H1_ELEMENT = "h1";
    case H2_ELEMENT = "h2";
    case H3_ELEMENT = "h3";
    case H4_ELEMENT = "h4";
    case H5_ELEMENT = "h5";
    case H6_ELEMENT = "h6";
    case P_ELEMENT = "p";
    case PRE_ELEMENT = "pre";
    case CODE_ELEMENT = "code";
    case B_ELEMENT = "b";
    case UL_ELEMENT = "ul";
    case OL_ELEMENT = "ol";
    case LI_ELEMENT = "li";
    case TABLE_ELEMENT = "table";
    case THEAD_ELEMENT = "thead";
    case TBODY_ELEMENT = "tbody";
    case TR_ELEMENT = "tr";
    case TH_ELEMENT = "th";
    case TD_ELEMENT = "td";
    case BR_ELEMENT = "br";
}
