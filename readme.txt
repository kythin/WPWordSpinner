=== WordSpinner ===

Contributors: kythin

Donate link: http://goo.gl/tiMKz

Tags: google, spin, content, random, word, generator, seo, spintax, wordspinner

Requires at least: 2.8

Tested up to: 3.5

Stable tag: trunk



Build 'spinners' in your posts or pages to help avoid duplicate content penalties. Now spins Titles!




== Description ==

2.7.3: If a supported seo plugin is used, now also spins the page meta description.

2.7: Added code to make titles work with YOAST. Please email me if you have another seo plugin that titles don't spin on and I'll check it out.

2.6.2: Now allows you to only spin once per user session, so the user will see consistent spins as they move around your site. You must enable this if you want it though, and you might want to look up "PHP Sessions" to understand what it means.

2.6: Now spins post & page titles, and the page meta title, but you will have to turn it on in the settings first (just check the box and hit save).

"Spinning" text or content is an SEO term that means you craft a paragraph with interchangeable words throughout. This means that each time the page is viewed, the text changes either subtly or substantially (up to you) and often improves the SEO potential of your website as it appears as if the page changes each time Google accesses it.

WordSpinner is a freely available and distributable (GPL) plugin for Wordpress to help avoid duplicate content penalties from Google. This can be abused (if you put a LOT of work into rewriting someone else's article...), but I wrote it so that you can enable it for things like summary texts or put some spin words in the first paragraph of a post and a 'read more' link underneath, and you won't get penalised for having the first paragraph the same on your front page as your item page.


Other uses / features include:
* Widget included to spin blocks of text in sidebars
* You can also use the sidebar widget to spin different ads, images, etc.
* Spin body text of any page or post with a simple to understand format.
* Shortcodes to use in templates to spin Titles, Comments, basically anything you like!

(The more you spin the slower your page will load, but in most cases it's not even noticable).


== Installation & Useage ==

To use it, upload the wordspinner directory to your plugins folder, then activate it through the wordpress administration. Then, in any post in your site, simply use the following format anywhere in the post to build a list of spin words or phrases;



{spin1, spin2, spin3}



Which, when included with normal text around it will mean that something like this;

The {quick, orange coloured, horribly smelly and possibly rabid} {fox, underwear model} jumped over the {dog, small child, clown}.



Will output any of the following when the page is viewed (and more, this is just an example);

The quick underwear model jumped over the small child.

The horribly smelly and possibly rabid fox jumped over the dog.

The horribly smelly and possibly rabid fox jumped over the small child.




OTHER USES:
Obviously it's meant for subtle changes like {the, an, is, a} and stuff, but you can also use it to make crazy stuff up if you want. You could even generate a number or ID with it using something like {1,2,3,4}{a,b,c}{4,3,5,2,8}.... etc. I use the sidebar widget to spin advertising banner code, for example.

Another way I use it myself is a quick and dirty way to randomly show ads, using the Spin text widget. Just make sure your spintax characters aren't in the JS code if you're using JS snippets for ads (like adsense)!



Also, you can change the characters you use in the posts to designate the start, end and separater in each spinner by going to Design - > WordSpinner in the admin of your site. By default it's "{" "}" and "," respectively.

NEW: You can now use multiple levels of spinners, e.g. Your key is {1,2,3{4,5,6{7,8}}}
The logic for the example there is to output either 1 2 or 3, and if it's 3 then 4, 5 or 6 will follow, and if it's 6 then 7 or 8 will follow that.
Here's all the possibilities for that small example above:
Your key is 1
Your key is 2
Your key is 34
Your key is 35
Your key is 367
Your key is 368


== Changelog ==


2.7.2: If a supported seo plugin is used, now also spins the page meta description. (coded for YOAST seo plugin but others may also work now too).

2.7: Added code to make titles work with YOAST. Please email me if you have another seo plugin that titles don't spin on and I'll check it out.

2.6: Added option to spin content and titles only once per user session (as in it uses the PHP Session variables to hold the spinner results until the user clears cache or the session expires).

2.5: Finally added the ability to spin Titles! Also investigating a bug reported by a user where the left and right characters sometimes appear in the text, but cannot replicate it. If this happens to you, please send me details to kythin@gmail.com thanks.

2.4: Fixed a bug that caused the update reminder to not go away... Opted to just have a Settings link instead.

2.3: Added a shortcode that you can use OUTSIDE OF THE CONTENT AREA, in particularly within template files. This will let you spin headers, footers, custom widgets, etc. See the settings page for details.

2.2: Very small update to change the default 'Spintax' to use {} instead of [] since it messes with shortcodes. Thanks pokeraffz!

2.1: Performance improvements

2.0: 	
* Multi level spinning!
* Moved the settings page to be underneath "Plugins" instead of Appearance
* Confirmed working on WP 3.0

1.4: Fixed a bug that overwrote plain text widgets with spinning ones.



1.3: Added a widget to display spun text, so no manual WP changes are required anymore! Now requires 2.8+ thanks to the widget api changes.



1.2: Updated to work with current WP release, confirmed working on my 2.8.4 test site.



1.1: Bugfixes, added the ability to set the left and right characters to 2 digits (e.g. left could be {{ or {/ or {{ etc). Also checked what happens with blank strings, it works fine and actually ads more depth to the system. You can use it to add words to a paragraph instead of just spinning them.



1.0: Got the thing working, with basic config screen, for WP 2.6.1. It's so simple, it should work with pretty much any future version as long as the WP guys keep the legacy code functional. So far it supports any number of entries in each spinner, and no limits on the number of spinners in a page, but I haven't stress tested it with more than about 6, and I dunno what it will do if you feed it empty strings. Logically it'll just be a blank entry in the array. Hmm. Should test that.



== Feedback ==

For feature requests, updates and general questions, please post a comment on my blog:
http://www.kythin.com/web/wordspinner2/