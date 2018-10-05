# Notes

## Gajus\Dindent

For better formatting results, open /core/vendor/gajes/dindent/src/Indenter.php 
and comment out the following lines:

``
$input = str_replace("\t", '', $input);
$input = preg_replace('/\s{2,}/', ' ', $input);
``