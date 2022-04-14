let canvasimg = document.getElementById('canvasimg');
            let ctx = canvasimg.getContext('2d')

            var img = new Image();
            // img.onload = function() {
            //     ctx.drawImage(img, 0, 0, canvasimg.width, canvasimg.height);
            // }
            // img.src = "image2vector (3).svg";
            // console.log(ctx);



            var svg = document.getElementById("emb");

            //console.log(svg);

            svg.addEventListener("load",function(){
                var svgDoc = svg.contentDocument;
                // console.log("#doc of the object",svgDoc);
               
                var svg_xml = (new XMLSerializer).serializeToString(svgDoc);    //serializing the svg doc we set up from the object element
                // console.log("serialised svg ",svg_xml); 

                // make it base64
                var svg64 = btoa(svg_xml);
                var b64Start = 'data:image/svg+xml;base64,';

                // prepend a "header"
                var image64 = b64Start + svg64;

                // set it as the source of the img element
                img.onload = function() {
                    // draw the image onto the canvas
                    ctx.drawImage(img, 0, 0,canvasimg.width, canvasimg.height);
                }
                img.src = image64;                                               //draw it on canvas

                let colors = getColors(svg_xml);
                // console.log("colors in the svg ",colors);
               // console.log("colors in the svg ",colors.length);


                // var myJSON = JSON.stringify(colors);   
                // console.log(myJSON);  
                // console.log({ '#fbfbfb': 'orange','#c40404': 'green' });                                          //get svg colors to make list

 
             //   let new_str  = replaceColors(svg_xml, { '#c40404': 'black' });    //replace color red with black
                // var rpcolor = { '#fbfbfb': 'orange','#c40404': 'green' };
                // console.log(rpcolor);
               // let new_str  = replaceColors(svg_xml, { '#fbfbfb': 'orange','#c40404': 'green' }); 


       jQuery(document).on('click', '.reset-col', function(event) {




  window.setTimeout(  
      function() {    //add logo when add logo button is clicked.(here using enter as trigger)
  
 

var sounds = jQuery('#pset-col').val();

//console.log(sounds);

// var sounds = ['#BD572F', '#25FF60'];
var sounds = sounds.split(",");




var assoc = [];
for(var i=0; i<colors.length; i++) {
    assoc[colors[i]] = sounds[i];
}

// console.log(assoc);


    rpcolor = jQuery("#color").val();





    // var gg = { '#fbfbfb': rpcolor,'#c40404': 'green' };

    // console.log(JSON.stringify({ '#fbfbfb': 'orange','#c40404': 'green' }));

                let new_str  = replaceColors(svg_xml, assoc); 

                // console.log(gg);
                 var svg64 = btoa(new_str);
                var b64Start = 'data:image/svg+xml;base64,';

                // prepend a "header"
                var image64 = b64Start + svg64;

                // set it as the source of the img element
                img.onload = function() {
                    // draw the image onto the canvas
                    ctx.drawImage(img, 0, 0,canvasimg.width, canvasimg.height);
                }
                img.src = image64;


  },  
      10
  );


  });











            jQuery(document).on('click', '.ps-col', function(event) {




  window.setTimeout(  
      function() {    //add logo when add logo button is clicked.(here using enter as trigger)
  
 

var sounds = jQuery('#pset-col').val();

// console.log(sounds);

// var sounds = ['#BD572F', '#25FF60'];
var sounds = sounds.split(",");




var assoc = [];
for(var i=0; i<colors.length; i++) {
    assoc[colors[i]] = sounds[i];
}

// console.log(assoc);


    rpcolor = jQuery("#color").val();





    // var gg = { '#fbfbfb': rpcolor,'#c40404': 'green' };

    // console.log(JSON.stringify({ '#fbfbfb': 'orange','#c40404': 'green' }));

                let new_str  = replaceColors(svg_xml, assoc); 

                // console.log(gg);
                 var svg64 = btoa(new_str);
                var b64Start = 'data:image/svg+xml;base64,';

                // prepend a "header"
                var image64 = b64Start + svg64;

                // set it as the source of the img element
                img.onload = function() {
                    // draw the image onto the canvas
                    ctx.drawImage(img, 0, 0,canvasimg.width, canvasimg.height);
                }
                img.src = image64;


  },  
      10
  );


  });



                        jQuery('.s-color').change(function(){




  window.setTimeout(  
      function() {    //add logo when add logo button is clicked.(here using enter as trigger)
  
 

var sounds = jQuery('#s-set').val();

jQuery('#pset-col').val(sounds);



// var sounds = ['#BD572F', '#25FF60'];
var sounds = sounds.split(",");


var assoc = [];
for(var i=0; i<colors.length; i++) {
    assoc[colors[i]] = sounds[i];
}

// console.log(assoc);


    rpcolor = jQuery("#color").val();





    // var gg = { '#fbfbfb': rpcolor,'#c40404': 'green' };

    // console.log(JSON.stringify({ '#fbfbfb': 'orange','#c40404': 'green' }));

                let new_str  = replaceColors(svg_xml, assoc); 

                // console.log(gg);
                 var svg64 = btoa(new_str);
                var b64Start = 'data:image/svg+xml;base64,';

                // prepend a "header"
                var image64 = b64Start + svg64;

                // set it as the source of the img element
                img.onload = function() {
                    // draw the image onto the canvas
                    ctx.drawImage(img, 0, 0,canvasimg.width, canvasimg.height);
                }
                img.src = image64;


  },  
      100
  );


  });




                          if(document.getElementById("threedata").value == 1)

                  { 


var sounds = jQuery('#pre-color').val();

// console.log(sounds);

// var sounds = ['#BD572F', '#25FF60'];
var sounds = sounds.split(",");




var assoc = [];
for(var i=0; i<colors.length; i++) {
    assoc[colors[i]] = sounds[i];
}

// console.log(assoc);


    rpcolor = jQuery("#color").val();





    // var gg = { '#fbfbfb': rpcolor,'#c40404': 'green' };

    // console.log(JSON.stringify({ '#fbfbfb': 'orange','#c40404': 'green' }));

                let new_str  = replaceColors(svg_xml, assoc); 

                  var svg64 = btoa(new_str);
                var b64Start = 'data:image/svg+xml;base64,';

                // prepend a "header"
                var image64 = b64Start + svg64;

                // set it as the source of the img element
                img.onload = function() {
                    // draw the image onto the canvas
                    ctx.drawImage(img, 0, 0,canvasimg.width, canvasimg.height);
                }
                img.src = image64;








                  } else 



                  {

//var sounds = ['#F24CFF', '#FF4C4C'];
var assoc = [];
for(var i=0; i<colors.length; i++) {
    assoc[colors[i]] = colors[i];
}
// console.log(assoc);

 var myJSON = JSON.stringify(assoc);   
                // console.log(myJSON); 
                 let new_str  = replaceColors(svg_xml, assoc); 


                 var svg64 = btoa(new_str);
                var b64Start = 'data:image/svg+xml;base64,';

                // prepend a "header"
                var image64 = b64Start + svg64;

                // set it as the source of the img element
                img.onload = function() {
                    // draw the image onto the canvas
                    ctx.drawImage(img, 0, 0,canvasimg.width, canvasimg.height);
                }
                img.src = image64;    
                    
                  }
                   //replace color white with blue

                                                              //draw it after replacement


            }, false);

            function parseSVG(svgString) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(svgString, 'image/svg+xml');
                return doc;
            }

            function getElementColor(el) {
                return el.getAttribute('fill');
            }

            function getColors(svgString) {
                const doc = parseSVG(svgString);
                var elements = doc.getElementsByTagName('*');
                const usedColors = [];
                for (const element of elements) {
                    const color = getElementColor(element);
                    // if color is defined and uniq we will add it
                    if (color && usedColors.indexOf(color) === -1) {
                    usedColors.push(color);
                    }
                }
                return usedColors;
            }

            function replaceColors(svgString, map) {
                // we can do some RegExp magic here
                // but I will just manually check every element
                const doc = parseSVG(svgString);
                var elements = doc.getElementsByTagName('*');
                for (const element of elements) {
                    const color = getElementColor(element);

                    if (map[color]) {
                    element.setAttribute('fill', map[color]);
                    }
                }
                // serialize DOM back into string
                var xmlSerializer = new XMLSerializer();
                const str = xmlSerializer.serializeToString(doc);
                return str;
            }