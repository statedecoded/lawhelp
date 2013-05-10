# LawHelp

A PHP-based class to interface with LawHelp.org's API. It iterates through all legal self-help documents that they have indexed, gathering the useful bits of information and storing them in a single object.

The API is presently only a "preview release," but can be seen at [valegalaid.org](http://www.valegalaid.org/api/v2/). At present, LawHelp interfaces only with valegalaid.org, but it is probably perfectly capable of interfacing with implementations in other states as they come to exist, without further modifications.

## Usage
```
require('class.LawHelp.inc.php)
$lawhelp = new LawHelp();
$lawhelp->harvest_documents();

echo '<pre>' . print_r($lawhelp->topics, TRUE) . '</pre>';
```

## Notes
* Every time this runs, it makes over a dozen API calls. It is meant to be run to gather a list to be cached (basically harvesting a bulk download), and *not* to be called upon, say, every page load on a website.

## To Do
* Interface with the `organization` method to gather a list of all legal aid organizations located within the state in question.

## License
Released under the GNU Public License, v3.0.