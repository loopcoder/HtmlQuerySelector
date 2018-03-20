# ![logo](https://avatars1.githubusercontent.com/u/34467170?s=45) HtmlQuerySelector
 * Javascript `querySelectorAll` the same function for PHP.
 * I think, some features different and missing, by Javascript.

## Info
 `This version 17.12.20.01 (year.month.day.release)`

#### Parameters
 * @param string `$source`    HTML source
 * @param string `$selector`  HTML selector query

#### Returns
 * @return `array`

## Using
```
$html = '
<p>
  <b>
    #BOLD
  </b>
</p>
<p>
  <b>
    BOLD
  </b>
</p>
';
$test = 'p > b';
echo HtmlQuerySelector($html, $test)[0]; // #BOLD
echo HtmlQuerySelector($html, $test)[1]; // BOLD
```

#### Same Queries
| Javascript       | PHP              |
| --------------   | --------------   |
| .intro           | .intro           |
| #firstname       | #firstname       |
| p                | p                |
| div > p          | div > p          |
| div + p          | div + p          |
| [target]         | [target]         |
| a[href]          | a[href]          |
| p:nth-child(2)   | p:nth-child(2)   |

#### Different Queries
| Javascript    | PHP           |
| -----------   | -----------   |
| div > * > p   | div >  > p    |
| div , p       | :shit:        |
| div p         | div > p       |
| p ~ ul        | ul , p        |
| .intro.play   | .play.intro   |

#### New Queries
| Javascript   | PHP                      |
| ----------   | ----------------------   |
| :shit:       | b > strong < a           |
| :shit:       | b > :ret( strong ) < a   |
| :shit:       | :sub( .italic )          |
| :shit:       | :deep( .italic )         |

## Query Examples
| Query                                                     | Result                                        |
| -------------------------------------------------------   | -------------------------------------------   |
| p > b                                                     | #BOLD                                         |
| [href]                                                    | A HREF                                        |
| :sub( [href] :nth-child(2) )                              | B HREF                                        |
| :sub( :deep( .italic ) )                                  | .ITALIC                                       |
| :sub( :deep( .italic :nth-child(2) ) )                    | .ITALIC 2                                     |
| #search > :sub( b )                                       | #BOLD                                         |
| #search > :ret( .italic )                                 | .ITALIC                                       |
| p#search > :ret( b ) < u                                  | #BOLD                                         |
| p#search > i.italic + b > strong < :ret( a ) < u          | A                                             |
| p#search > i.italic + b > strong < :ret( a[href] ) < u    | A HREF                                        |
| p > ~img[src]                                             | #IMG SRC                                      |
| p > :sub( ~img[src] )                                     | #IMG SRC                                      |
| p#search > i.italic + b > strong < a < :ret( u )          | UNDERLINE                                     |
| p#search > :sub( b :nth-child(2) ) > :ret( strong ) < a   | STRONG                                        |
| p#search > :ret( :deep( ) ) + ~b > ~strong                | #BOLD .ITALIC .ITALIC 2 .ITALIC 3 .ITALIC 4   |

## Complete Examples
``` PHP
echo '<meta charset="UTF-8">
<style type="text/css">
body{font-size:12px;background-color:#000;color:#fff;font-family:verdana}
</style>'; // code for nice image

$html = '
<p id="search">
  <b id="bold">
    #BOLD
  </b>
  <i class="italic">
    .ITALIC
  </i>
  <i class="italic">
    .ITALIC 2
  </i>
  <i class="italic">
    .ITALIC 3
  </i>
  <i class="italic">
    .ITALIC 4
  </i>
  <b id="bold2">
    #BOLD 2
    <strong>
      STRONG
    </strong>
  </b>
  <a href="A HREF">
    A
  </a>
  <a href="B HREF">
    B
  </a>
</p>
<p>
    <img src="IMG SRC">
    <img src="IMG SRC 2">
    <b>
      BOLD
    </b>
</p>
<u>
  UNDERLINE
</u>
';

$google = file_get_contents('https://www.google.com', false, stream_context_create(array('http'=>array('header'  => 'User-agent: Mozilla/5.0 Firefox/40.1'))));
echo HtmlQuerySelector($google, ':deep( .gb_Q.gb_R :nth-child(2) ) > a.gb_P')[0] . '<br><br>'; // Images

$test = 'p > b';
echo HtmlQuerySelector($html, $test)[0] . '<br>'; // #BOLD
echo HtmlQuerySelector($html, $test)[1] . '<br><br>'; // BOLD

$test = '[href]';
echo HtmlQuerySelector($html, $test)[0] . '<br><br>'; // A HREF

$test = ':sub( [href] :nth-child(2) )';
echo HtmlQuerySelector($html, $test)[0] . '<br><br>'; // B HREF

$test = ':sub( :deep( .italic ) )';
echo HtmlQuerySelector($html, $test)[0] . '<br>'; // .ITALIC
echo HtmlQuerySelector($html, $test)[1] . '<br><br>'; // .ITALIC 2

$test = ':sub( :deep( .italic :nth-child(2) ) )';
echo HtmlQuerySelector($html, $test)[0] . '<br>'; // .ITALIC 2
echo HtmlQuerySelector($html, $test)[1] . '<br><br>'; // .ITALIC 4

$test = '#search > :sub( b )';
echo HtmlQuerySelector($html, $test)[0] . '<br>'; // #BOLD
echo HtmlQuerySelector($html, $test)[1] . '<br><br>'; // # BOLD 2 STRONG

$test = '#search > :ret( .italic )';
echo HtmlQuerySelector($html, $test)[0] . '<br><br>'; // .ITALIC

$test = 'p#search > :ret( b ) < u';
echo HtmlQuerySelector($html, $test)[0] . '<br><br>'; // #BOLD

$test = 'p#search > i.italic + b > strong < :ret( a ) < u';
echo HtmlQuerySelector($html, $test)[0] . '<br><br>'; // A

$test = 'p#search > i.italic + b > strong < :ret( a[href] ) < u';
echo HtmlQuerySelector($html, $test)[0] . '<br><br>'; // A HREF

$test = 'p > ~img[src]';
echo HtmlQuerySelector($html, $test)[0] . '<br><br>'; // #IMG SRC

$test = 'p > :sub( ~img[src] )';
echo HtmlQuerySelector($html, $test)[0] . '<br>'; // #IMG SRC
echo HtmlQuerySelector($html, $test)[1] . '<br><br>'; // #IMG SRC 2

$test = 'p#search > i.italic + b > strong < a < :ret( u )';
echo HtmlQuerySelector($html, $test)[0] . '<br><br>'; // UNDERLINE

$test = 'p#search > :sub( b :nth-child(2) ) > :ret( strong ) < a';
echo HtmlQuerySelector($html, $test)[0] . '<br><br>'; // STRONG

$test = 'p#search > :ret( :deep( ) ) + ~b > ~strong';
echo HtmlQuerySelector($html, $test)[0] . '<br><br>'; // #BOLD .ITALIC .ITALIC 2 .ITALIC 3 .ITALIC 4
```
