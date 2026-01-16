setTimeout(() => {
    const flash = document.querySelector('.flash-message');
    if (flash) {
        flash.style.transition = "opacity 1s"
        flash.style.opacity = "0";
        setTimeout(() => flash.remove(), 1000);
    }
}, 3000);

const productsLink = document.querySelector(".products-menu");
const productsSubmenu = document.querySelector(".products-submenu");
const arrow = productsLink.querySelector(".arrow");/*The reason I am not just writing document.querySelector(".arrow") is because the latter will search for arrows in the document then return the first one so if you have multiple collapsible menus, it'll only work for the first one
but with productsLink.querySelector(".arrow") it only checks inside this class. If you want another menu, u'll have to do this for the other menu too */

productsLink.addEventListener("click", function(e) {
    /*For every action listener, you must always add an event so here the event is click. Others events include: dblclick(double click), 
    submit(when form is submitted), scroll etc
    When you attach an event listener, the browser automatically passes an event object(info on what has just happened eg the action
    performed(click), what has been clicked etc). It always exists but it is not always used(passed as a parameter unless necessary).
    Here, we've passed e as a parameter. Think of e as the “receipt” the browser hands you whenever something happens. You don’t always
    need to look at the receipt, but it’s there if you want details or control. So e is details of what has happened to productsLink.
    It always exists but when you want to use it, you pass it as a parameter then use it. The letter doesn't have to be e, it could be 
    anything. Below, we'll use it to prevent the page from reloading every time the link has been clicked cz that's what it would do 
    normally. If I didn't need to use the event object(e), I'd just write the function like this: function()*/

    e.preventDefault(); //prevents page reload

    productsSubmenu.style.display = productsSubmenu.style.display === "block" ? "none" : "block";/*This means that if the submenu's 
    display is none(like the whole div, u'll notice that in the css I made the individual a tags block. That is not what I mean here,
    I mean the whole div carrying the links and by default I have set it as none), set it to block meaning let it appear cz divs are
    block elements but if it is set to block, let the display disappear.*/

    arrow.classList.toggle("open");
    /*Every HTML element has a class list which is the list of classes that applies to it, classList identifies the classes.
    toggle means that if the classlist includes a class called open, remove it. If it doesn't create it(and we have specified the 
    styling of the class open in css if it is created). So in short this statement is saying:
    Check if the arrow element has the class open. If it does, remove it. If it doesn’t, add it. 
    It is used to make the arrow change everytime it is clicked*/
});