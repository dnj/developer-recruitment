import './clock.scss';
const Clock = () => {
    const degreeLocationHours = [15,30,45,60,75,105,120,135,150,165];
    const getStyleHours = (degreeLocationHours)=>{
        return {
            transform : `rotate(${degreeLocationHours}deg)`,
        }
    }
    return (
        <>
            <div className="clock">
                <div className="outerClockFace">
                    <span className="hour-00">00</span>
                    <span className="hour-06">06</span>
                    <span className="hour-12">12</span>
                    <span className="hour-18">18</span>
                    {degreeLocationHours.map((degreeLocationHour, index) => (
                        <div key={index} className="symbol-hour" style={getStyleHours(degreeLocationHour)}></div>
                    ))}
                </div>
                <div className="innerClockFace"></div>
            </div>

        </>
    )
}

export default Clock;
