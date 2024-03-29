<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Textarea Autocomplete</title>

    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <style type="text/css" media="screen">
      * { padding: 0; margin: 0; }
      html, body { height: 100%; }
      body, textarea {
         font-family: "lucida sans", verdana, arial, helvetica, sans-serif;
         font-size: 80%;
      }
      textarea { font-size: 100%;}
      h1 { font-size: 1.4em; padding: 10px 10px 0; }
      h2 { font-size: 1.2em; padding: 10px 10px 0; color:Navy;}
      p { margin-top: 6px; padding: 0 10px 1em; }

    .ui-widget { font-size: 0.8em; line-height:0.6em; }
    .ui-widget .ui-widget { font-size: 0.7em; }

    .ui-menu-item { font-size: 7pt; }

      #inputDiv {
         left: 1%;
         right: 38%;
      }

      #msgs {
         left: 63%;
         right: 1%;
      }

      div.column {
         border: 1px dotted #666;
         background-color: #E6E6FA;
         position: absolute;
         top: 20px;    /* margin from top */
         bottom: 20px; /* margin from bottom */
         padding: 8px;
      }
      * html  {
         height: 100%;
      }

    </style>
  <body>
    <div id='inputDiv' class='column'>
      <h1>jQueryUI Autocomplete demo</h1>
      <p>This demo uses jQueryUI v1.8rc3.</p>
      <p>Type a few characters of a word. Try these: <em>Four, train, even.</em></p>
      <form action="jquery" id="form1">
        <textarea rows='14' cols='80' id="input1"></textarea>

      </form>
    </div>

    <div id='msgs' class='column' style='font-size:10pt;font-weight:normal;color:red;'>
    </div>
    <script>
    var phraselist = [
    '"Until he extends the circle of his compassion to all living things, man will not himself find peace."  Albert Schweitzer, early 20th-century German Nobel Peace Prize-winning mission doctor and theologian',
  'First they came for the communists, and I did not speak out—because I was not a communist; Then they came for the socialists, and I did not speak out—because I was not a socialist; Then they came for the trade unionists, and I did not speak out—because I was not a trade unionist; Then they came for the Jews, and I did not speak out—because I was not a Jew; Then they came for me—and there was no one left to speak out for me. -- Pastor Martin Niemöller',
  'I and the public know // what all schoolchildren learn // Those to whom evil is done // do evil in return.   --W H Auden.',

  'Let us not wallow in the valley of despair, I say to you today, my friends. And so even though we face the difficulties of today and tomorrow, I still have a dream. It is a dream deeply rooted in the American dream. I have a dream that one day this nation will rise up and live out the true meaning of its creed: "We hold these truths to be self-evident, that all men are created equal." I have a dream that one day on the red hills of Georgia, the sons of former slaves and the sons of former slave owners will be able to sit down together at the table of brotherhood. I have a dream that one day even the state of Mississippi, a state sweltering with the heat of injustice, sweltering with the heat of oppression, will be transformed into an oasis of freedom and justice. I have a dream that my four little children will one day live in a nation where they will not be judged by the color of their skin but by the content of their character.  I have a dream today! I have a dream that one day, down in Alabama, with its vicious racists, with its governor having his lips dripping with the words of "interposition" and "nullification" -- one day right there in Alabama little black boys and black girls will be able to join hands with little white boys and white girls as sisters and brothers. I have a dream today!',
  'I\'d like to be under the sea in an octopus\' garden, in the shade; He\'d let us in, knows where we\'ve been -- in his octopus\' garden, in the shade.',

  'Four score and seven years ago our fathers brought forth upon this continent a new nation, conceived in liberty, and dedicated to the proposition that all men are created equal.  Now we are engaged in a great civil war, testing whether that nation, or any nation so conceived and so dedicated, can long endure. We are met here on a great battlefield of that war. We have come to dedicate a portion of it as a final resting place for those who here gave their lives that that nation might live. It is altogether fitting and proper that we should do this. But in a larger sense we can not dedicate - we can not consecrate - we can not hallow this ground. The brave men, living and dead, who struggled here, have consecrated it far above our poor power to add or detract. The world will little note, nor long remember, what we say here, but can never forget what they did here. It is for us, the living, rather to be dedicated here to the unfinished work which they have, thus far, so nobly carried on. It is rather for us to be here dedicated to the great task remaining before us that from these honored dead we take increased devotion to that cause for which they here gave the last full measure of devotion - that we here highly resolve that these dead shall not have died in vain; that this nation shall have a new birth of freedom; and that government of the people, by the people, for the people, shall not perish from the earth.',
  'The Hypertext Transfer Protocol (HTTP) is an application-level protocol for distributed, collaborative, hypermedia information systems. It is a generic, stateless, protocol which can be used for many tasks beyond its use for hypertext, such as name servers and distributed object management systems, through extension of its request methods, error codes and headers [47]. A feature of HTTP is the typing and negotiation of data representation, allowing systems to be built independently of the data being transferred.',

  'What would things have been like [in Russia] if during periods of mass arrests people had not simply sat there, paling with terror at every bang on the downstairs door and at every step on the staircase, but understood they had nothing to lose and had boldly set up in the downstairs hall an ambush of half a dozen people? -- Alexander Solzhenitsyn',

  'The good will is not good because of what it effects or accomplishes or because of its competence to achieve some intended end: it is good only because of its willing (i.e. it is good in itself).  Even if it should happen that, by a particularly unfortunate fate or by the niggardly provision of a step-motherly nature, this will should be wholly lacking in power to accomplish its purpose, and if even the greatest effort should not avail it to achieve anything of its end, and if there remained only the good will-not as a mere wish, but as the summoning of all the means in our power- it will sparkle like a jewel all by itself, as something that had its full worth in itself.',

  'When in the Course of human events, it becomes necessary for one people to dissolve the political bands which have connected them with another, and to assume among the powers of the earth, the separate and equal station to which the Laws of Nature and of Nature\'s God entitle them, a decent respect to the opinions of mankind requires that they should declare the causes which impel them to the separation. // We hold these truths to be self-evident, that all men are created equal, that they are endowed by their Creator with certain unalienable Rights, that among these are Life, Liberty and the pursuit of Happiness. --That to secure these rights, Governments are instituted among Men, deriving their just powers from the consent of the governed, --That whenever any Form of Government becomes destructive of these ends, it is the Right of the People to alter or to abolish it, and to institute new Government, laying its foundation on such principles and organizing its powers in such form, as to them shall seem most likely to effect their Safety and Happiness. Prudence, indeed, will dictate that Governments long established should not be changed for light and transient causes; and accordingly all experience hath shewn, that mankind are more disposed to suffer, while evils are sufferable, than to right themselves by abolishing the forms to which they are accustomed. But when a long train of abuses and usurpations, pursuing invariably the same Object evinces a design to reduce them under absolute Despotism, it is their right, it is their duty, to throw off such Government, and to provide new Guards for their future security. --Such has been the patient sufferance of these Colonies; and such is now the necessity which constrains them to alter their former Systems of Government. The history of the present King of Great Britain [George III] is a history of repeated injuries and usurpations, all having in direct object the establishment of an absolute Tyranny over these States. To prove this, let Facts be submitted to a candid world.',

  'The object of a liberal training is not learning, but discipline and the enlightenment of the mind. The educated man is to be discovered by his point of view, by the temper of his mind, by his attitide towards life and his fair way of thinking.  He can see, he can discriminate, he can combine ideas and perceive whither they lead; he has insight and compehension. His mind is a practised instrument of appreciation.  He is more apt to contribute light than heat to a discussion, and will oftener than another show the power of uniting the elements of a difficult subject in a whole view; he has the knowledge of the world which no one can have who knows only his own generation or only his own task. // What we should seek to impart in our colleges therefore, is not so much learning itself as the spirit of learning.  You can impart that to young men; and you can impart it to them in the three or four years at your disposal.  It consists in the power to distinguish good reasoning from bad, in the power to digest and interpret evidence, in a habit of catholic observation and a preference for the non-partisan point of view, in an addiction to clear and logical processes of thought and yet an instinctive desire to interpret rather than to stick in the letter of the reasoning, in a taste for knowledge and a deep respect for the integrity of the human mind.  It is citizenship of the world of knowledge, but not ownership of it. '
    ];
    phraselist.sort();

    $(document).ready(function() {
        monkeyPatchAutocomplete();

        $("#input1").autocomplete({
            // The source option can be an array of terms.  In this case, if
            // the typed characters appear in any position in a term, then the
            // term is included in the autocomplete list.
            // The source option can also be a function that performs the search,
            // and calls a response function with the matched entries.
            source: function(req, responseFn) {
                addMessage("search on: '" + req.term + "'<br/>");
                var re = $.ui.autocomplete.escapeRegex(req.term);
                var matcher = new RegExp("\\b" + re, "i" );
                var a = $.grep( phraselist, function(item,index){
                    //addMessage("&nbsp;&nbsp;sniffing: '" + item + "'<br/>");
                    return matcher.test(item);
                });
                addMessage("Result: " + a.length + " items<br/>");
                responseFn( a );
            },

            select: function(value, data){
                var s = ""
                if (typeof data == "undefined") {
                  s = value;
                }else {
                  s = data.item.value;
                }
                if (s.length > 30) { s = s.substring(0,30) + "..."; }
                addMessage('You selected: ' + s + "<br/>");
            }
        });
    });

    // This patches the autocomplete render so that
    // matching items have the match portion highlighted.
    function monkeyPatchAutocomplete() {

        // Don't really need to save the old fn,
        // but I could chain if I wanted to
        var oldFn = $.ui.autocomplete.prototype._renderItem;

        $.ui.autocomplete.prototype._renderItem = function( ul, item) {
            var re = new RegExp("\\b" + this.term, "i") ;
            var t = item.label.replace(re,"<span style='font-weight:bold;color:Blue;'>" + this.term + "</span>");
            return $( "<li></li>" )
                .data( "item.autocomplete", item )
                .append( "<a>" + t + "</a>" )
                .appendTo( ul );
        };
    }

  function addMessage(msg){
      $('#msgs').append(msg+"<br/>");
  }
    </script>
  </body>
</html>
