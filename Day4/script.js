// ================================
// COUNTDOWN TIMER
// ================================

const eventDate = new Date("July 10, 2026 09:00:00").getTime();

const countdown = setInterval(() => {

    const now = new Date().getTime();

    const distance = eventDate - now;

    if (distance <= 0) {

        clearInterval(countdown);

        document.getElementById("days").innerHTML = "00";
        document.getElementById("hours").innerHTML = "00";
        document.getElementById("minutes").innerHTML = "00";
        document.getElementById("seconds").innerHTML = "00";

        return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));

    const hours = Math.floor(
        (distance % (1000 * 60 * 60 * 24)) /
        (1000 * 60 * 60)
    );

    const minutes = Math.floor(
        (distance % (1000 * 60 * 60)) /
        (1000 * 60)
    );

    const seconds = Math.floor(
        (distance % (1000 * 60)) /
        1000
    );

    document.getElementById("days").innerHTML = days;
    document.getElementById("hours").innerHTML = hours;
    document.getElementById("minutes").innerHTML = minutes;
    document.getElementById("seconds").innerHTML = seconds;

},1000);


// ================================
// FADE IN ON SCROLL
// ================================

const observer = new IntersectionObserver(entries=>{

    entries.forEach(entry=>{

        if(entry.isIntersecting){

            entry.target.classList.add("show");

        }

    });

},{
    threshold:0.2
});

document.querySelectorAll(".fade").forEach(section=>{

    observer.observe(section);

});


// ================================
// SCROLL TO TOP
// ================================

const topBtn=document.getElementById("topBtn");

window.onscroll=function(){

    if(document.documentElement.scrollTop>400){

        topBtn.style.display="block";

    }
    else{

        topBtn.style.display="none";

    }

};

topBtn.onclick=function(){

    window.scrollTo({

        top:0,

        behavior:"smooth"

    });

};


// ================================
// SIMPLE CONFETTI
// ================================

const canvas=document.getElementById("confetti");

const ctx=canvas.getContext("2d");

canvas.width=window.innerWidth;

canvas.height=window.innerHeight;

let pieces=[];

for(let i=0;i<180;i++){

    pieces.push({

        x:Math.random()*canvas.width,

        y:Math.random()*canvas.height-canvas.height,

        r:Math.random()*6+2,

        d:Math.random()*180,

        color:[
            "#D4AF37",
            "#FFD700",
            "#FFFFFF",
            "#F5D76E"
        ][Math.floor(Math.random()*4)],

        tilt:Math.random()*10,

        speed:Math.random()*3+2

    });

}

function draw(){

    ctx.clearRect(0,0,canvas.width,canvas.height);

    pieces.forEach(p=>{

        ctx.beginPath();

        ctx.fillStyle=p.color;

        ctx.arc(

            p.x,

            p.y,

            p.r,

            0,

            Math.PI*2

        );

        ctx.fill();

    });

    update();

}

function update(){

    pieces.forEach(p=>{

        p.y+=p.speed;

        p.x+=Math.sin(p.d);

        p.d+=0.01;

        if(p.y>canvas.height){

            p.y=-20;

            p.x=Math.random()*canvas.width;

        }

    });

}

setInterval(draw,20);


// ================================
// RESIZE CANVAS
// ================================

window.addEventListener("resize",()=>{

    canvas.width=window.innerWidth;

    canvas.height=window.innerHeight;

});