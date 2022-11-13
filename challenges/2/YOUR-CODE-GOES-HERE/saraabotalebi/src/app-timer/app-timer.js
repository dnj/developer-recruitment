import Clock from './../components/clock/clock';
import Timer from './../components/timer/timer';
import './app-timer.scss';

const AppTimer = ()=>{
  return(
    <div className="AppTimer">
      <Clock/>
      <Timer activeHours={[{start:5,end:13},{start:22,end:24}]}/>
    </div>
  )
}

export default AppTimer;