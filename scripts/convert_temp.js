window.addEventListener("DOMContentLoaded", domLoaded);

// When the DOM has finished loading, add the event listeners.
function domLoaded() {
   // TODO: Use addEventListener() to register a click event handler for the convert button.
   // https://developer.mozilla.org/en-US/docs/Web/API/EventTarget/addEventListener#add_a_simple_listener

   // Add event listeners to handle clearing the box that WAS NOT clicked,
   // e.g., the element C_in listens for 'input', with a callback fn to
   // execute after that event does happen. 
   // You don't send arguments to the event handler function.
   // So, if you want the event handler to call another function that
   // DOES take arguments, you can send that other function as a callback.
   // https://developer.mozilla.org/en-US/docs/Web/API/EventTarget/addEventListener#event_listener_with_anonymous_function
   // Here is an example of anonymous event handler fn that calls alert with an argument:
   // document.getElementById("weatherIcon").addEventListener("click", function() {alert("You clicked the icon.")})



   document.getElementById("C_in").addEventListener("input", function() {
      document.getElementById("F_in").value = "";
   });

   document.getElementById("F_in").addEventListener("input", function() {
      document.getElementById("C_in").value = "";
   });

   document.getElementById("convertButton").addEventListener("click", function() {
       let C_in = document.getElementById("C_in").value;
       let F_in = document.getElementById("F_in").value;
       let result;
       let weather;
         if (C_in !== "") {
               result = convertCtoF(parseFloat(C_in));
               document.getElementById("F_in").value = result.toFixed(2);
               document.getElementById("C_in").value = "";
               weather = icon(result);
               document.getElementById("weatherIcon").src = "images/" + weather + ".png";
         } else if (F_in !== "") {
               result = convertFtoC(parseFloat(F_in));
               document.getElementById("C_in").value = result.toFixed(2);
               document.getElementById("F_in").value = "";
               weather = icon(parseFloat(F_in));
               document.getElementById("weatherIcon").src = "images/" + weather + ".png";
         } else {
               document.getElementById("weatherIcon").src = "images/C-F.png";
               alert("Enter a temperature to convert.");
         }
   });
}

// TODO: (Part of the above is to write the functions to be executed when the event handlers are invoked.)

function convertCtoF(C) {
   
   // TODO: Return temp in °F. 
   // °F = °C * 9/5 + 32
   return C * 9/5 + 32;
}

function convertFtoC(F) {
   // TODO: Return temp in °C. 
   // °C = (°F - 32) * 5/9
   return (F - 32) * 5/9;
}

// TODO: write a fn that can be called with every temp conversion
// to display the correct weather icon.
// Based on degrees Fahrenheit:
// 32 or less, but above -200: cold
// 90 or more, but below 200: hot
// between hot and cold: cool
// 200 or more, -200 or less: dead
// both input fields are blank: C-F

function icon(F) {
   if (F <= 32 && F > -200) {
       return "cold";
   } else if (F >= 90 && F < 200) {
       return "hot";
   } else if (F > 32 && F < 90) {
       return "cool";
   } else {
       return "dead";
   }
}
