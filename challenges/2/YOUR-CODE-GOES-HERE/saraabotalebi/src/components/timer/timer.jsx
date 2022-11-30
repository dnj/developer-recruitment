import './timer.scss';
const Timer = ({activeHours})=>{
    const getStyleTimer = (start,end)=>{
        
        const startTimer = start*15;
        const endTimer = (end-start)*15-0.5;
        return({
            transform : `translate(-50%,-50%) rotate(${startTimer}deg)`,
            backgroundImage: `conic-gradient(orangered 0deg ${endTimer}deg, transparent ${endTimer}deg)`,
        });
    }
    return(
        <>
            {activeHours.map((activeHour,index)=>(
                <div key={index} className="Timer" style={getStyleTimer(activeHour.start,activeHour.end)}></div>
                ))
            }
        </>

    )
}
export default Timer;
