<template>
    <section>
        <template v-for="(arc,index) in getArcs" :key="index">
            <svg viewBox='0 0 100 100' :style="[ arc ? {'transform': 'rotate(' +  arc.startAngle + 'deg)'} : false]">
                <circle cx='50' cy='50' r='45' id='red' :style="[ arc ? {'stroke-dasharray': 280 ,'stroke-dashoffset': arc.dashoffset }: false]" />
            </svg>
        </template>
        
        <div class="hourhand" :style="`transform: rotate(${hour}deg)`" :class="activeHour ? 'active_hour' : 'a'"></div>
        <div class="secondhand" :style="`transform: rotate(${second}deg)`" :class="activeHour ? 'active_hour' : 'a'"></div>
        <div class="minutehand" :style="`transform: rotate(${minute}deg)`" :class="activeHour ? 'active_hour' : 'a'"></div>
        <div class="hour12">
            <div class="left">18</div>
            <div class="right">06</div>
        </div>
        <div class="hour1"></div>
        <div class="hour2"></div>
        <div class="hour3">
            <div class="top">00</div>
            <div class="bottom">12</div>
        </div>
        <div class="hour4"></div>
        <div class="hour5"></div>
        <div class="hour6"></div>
        <div class="hour7"></div>
        <div class="hour8"></div>
        <div class="hour9"></div>
        <div class="hour10"></div>
        <div class="hour11"></div>
    </section>
</template>

<script>
export default {
    name: 'AnalogClock',
    props : {
       activeHours: {
        type: Array,
        require: false,
        validator(value) {  //check input is valid
            value.forEach(item => {
                if(item.start && item.end) {
                    if(Number.isInteger(item.start) && Number.isInteger(item.end)) {
                        if(!(item.start >= 0 && item.start <=24 && item.end >= 0 && item.end <=24 && item.start < item.end)) {
                            console.error("error: start should be grater then end and both (start and end) grther or equal 0")
                            return false
                        }
                    } else {
                        console.error("error: start and end should be a integer number")
                        return false
                    }
                } else {
                    console.error("error: the object should be contains start and end property")
                    return false
                }
            })
            return true
         },
       }
    },
    data() {
        return {
            hour: 0,
            minute: 0,
            second: 0,
            activeHour: false
        }
    },
    mounted() {
        setInterval(this.clock, 1000);
    },
    methods: {
        clock() {   // calculate the clock hand degree
            const date = new Date();
            this.hour = date.getHours() * 15 - 90;
            this.minute = date.getMinutes() * 6 - 90;
            this.second = date.getSeconds() * 6 - 90;
        },
        checkArc() {    // check the clock hand hour is in the arcs of active hour
            const hour = new Date().getHours()
            if(this.getArcs) {
                this.getArcs.forEach(item => {
                    if(hour >= item.start && hour <= item.end) {
                        this.activeHour = true
                    } else {
                        this.activeHour = false
                    }
                })
            } else {
                this.activeHour =false
            }
            return this.activeHour
        }
    }, 
    computed: {
        getArcs() { //get arcs of active hour from input
            const HourAngle = 280/24
            const HourDegree = 360/24
            if(this.activeHours && Array.isArray(this.activeHours)) {
                const outputArray = []
                this.activeHours.forEach(item => {
                    const startAngle = HourDegree * item.start - 90
                    const dashoffset = HourAngle * (24 -(item.end - item.start))
                    outputArray.push({dashoffset, startAngle, end: item.end , start: item.start})
                });
                return outputArray
            }
            return false
        },
    },
    watch: {
        hour() {    // check the clock hand hour is in the arcs of active hour when change hour
            this.checkArc()
        }
    }

}
</script>

<style scoped>
svg {
    position: absolute;
    top: 0;
    z-index: 1;
    transform: rotate(-90deg);
}
circle {
  stroke-width: 4px;
  fill: transparent;
}
#red{
  stroke: rgba(182, 6, 6, 0.911);
  stroke-dasharray: 0;
  stroke-dashoffset: 0;
}

section {
    width: 80vmin;
    height: 80vmin;
    margin: auto;
    background: #fff ;
    border: 3vmin solid #000;
    border-radius: 50%;

    margin-top: 3vmin;
    box-shadow: 
        inset 40px 40px 90px rgba(0,0,0,.2),
        inset 10px 10px 30px rgba(0,0,0,.5), 
        20px 20px 30px rgba(0,0,0,.4),
        40px 40px 60px rgba(0,0,0,.4);
    position: relative;
    z-index: 0;
}

section:before {
    content: '';
    width: 95%;
    height: 95%;
    border-radius: 50%;
    display: block;
    background: transparent;
    border: 2vmin solid white;
}

section:after {
    content: '';
    width: 105%;
    height: 105%;
    border-radius: 50%;
    display: block;
    background: transparent;
    position: absolute;
    top: -2.5%;
    left: -2.5%;
    box-shadow: -3px -3px 9px rgba(255,255,255,.8);
}

.label {
    position: absolute;
    top: 19vmin;
    left: 46%;
    font-size: 2.5vmin;
}

.hourhand,
.secondhand,
.minutehand {
    width: 25vmin;
    height: 2vmin;
    background: #000;
    position: absolute;
    top: 40vmin;
    left: calc(50% - 3.5vmin);
    z-index: 2;
    transform: rotate(-139deg);
    transform-origin: 16%;
    -webkit-filter: drop-shadow(12px 12px 7px rgba(0,0,0,0.5));
	drop-shadow: (12px 12px 7px rgba(0,0,0,0.5));
}

.hourhand:after,
.secondhand:after,
.minutehand:after {
    content: '';
    background: #000;
    width: 5vmin;
    height: 5vmin;
    border-radius: 50%;
    z-index: 3;
    position: absolute;
    top: -1.5vmin;
    left: 1.5vmin;
}

.hourhand {
    border-top-right-radius: 20%;
    border-bottom-right-radius: 20%;
    box-shadow: -10px 0px 10px rgba(0,0,0,.4);
}

.minutehand {
    width: 40vmin;
    height: 1vmin;
    top: 40.5vmin;
    transform: rotate(-39deg);
    transform-origin: 10%;
    border-top-right-radius: 30%;
    border-bottom-right-radius: 30%;
    box-shadow: -10px 10px 10px rgba(0,0,0,.4);
}

.minutehand:before {
        content: '';
        width: 4.5vmin;
        height: 4.5vmin;
        border-radius: 50%;
        z-index: 99;
        position: absolute;
        top: -1.7vmin;
        left: 1.7vmin;
        box-shadow: -2px -2px 7px rgba(255,255,255,.6);
    }

.minutehand:after {
    top: -2vmin;
}

.secondhand {
    width: 35vmin;
    height: .5vmin;
    top: 40.75vmin;
    transform: rotate(160deg);
    transform-origin: 11.5%;
    box-shadow: -10px -10px 10px rgba(0,0,0,.4);
}
.secondhand:after {
    top: -2.25vmin;
}

.active_hour {
    background: rgba(228, 4, 4, 0.952);
}

.hour1,
.hour2,
.hour4,
.hour5,
.hour6,
.hour7,
.hour8,
.hour9,
.hour10,
.hour11 {
    height: 1vmin;
    width: 55vmin;
    background: transparent;
    border-left: 7vmin solid #000;
    border-right: 7vmin solid #000;
    transform: translate(-50%, -50%);
    top: 50%;
    left: 50%;
    position: absolute;
}

.hour12,
.hour3 {
    height: 1vmin;
    width: 55vmin;
    background: transparent;
    transform: translate(-50%, -50%);
    position: absolute;
}

.hour12 {
    top: 46%;
    left: 50%;
}

.hour12 .left,
.hour12 .right
 {
    position: absolute;
    font-size: 7vmin;
}
.hour12 .left { left: -7vmin !important; }

.hour12 .right { right: -7vmin !important; }

.hour3 {
    top: 50%;
    left: 63%;
    transform: rotate(90deg) translate(0, 34vmin);
}

.hour3 .top,
.hour3 .bottom
{
    transform: rotate(-90deg);
    position: absolute;
    font-size: 7vmin;
}
.hour3 .top { left: -8vmin !important; }

.hour3 .bottom { right: -7vmin !important; }
.hour1 { transform: rotate(105deg) translate(9vmin, 32.5vmin); }

.hour2 { transform: rotate(120deg) translate(17vmin, 29vmin); }

.hour4 { transform: rotate(135deg) translate(24vmin, 23.5vmin); }

.hour5 { transform: rotate(150deg) translate(29.5vmin, 16.5vmin); }

.hour6 { transform: rotate(165deg) translate(33vmin, 8.5vmin); }

.hour7 { transform: rotate(195deg) translate(32.5vmin, -9vmin); }

.hour8 { transform: rotate(210deg) translate(29vmin, -17vmin); }

.hour9 { transform: rotate(225deg) translate(23.5vmin, -24vmin); }
.hour10 { transform: rotate(240deg) translate(16.5vmin, -29.5vmin); }
.hour11 { transform: rotate(255deg) translate(8.5vmin, -32.5vmin); }
</style>