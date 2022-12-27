import React, { useState } from 'react'
import PowerButton from './components/powerButton/powerButton'
import SpeedController from './components/speedController/speedController'
import powerIcon from './assets/power.svg'
import arrowIcon from './assets/arrows-left-right.svg'
import './stylesheets/App.scss'

enum PowerStatus { 
  ON = 'روشن',
  OFF= 'خاموش'
}

enum OscillateStatus { 
  ON = 'فعال',
  OFF= 'غیر فعال'
}

enum SpeedStatus {
  ULTRA_LOW,
  LOW,
  MIDDLE,
  HIGH,
  TORNADO
}

function App(){

  const [powerStatus, setPowerStatus] = useState(PowerStatus.OFF);
  const [oscillateStatus, setOscillateStatus] = useState(OscillateStatus.OFF);
  const [speedStatus, setSpeedStatus] = useState(SpeedStatus.MIDDLE);
  



  return (
    <div className="App" dir='rtl'>
      <div className='window'>

        {/* fan */}
        <div className='section' id='fanSection'>
          
        </div>

        {/* status */}
        <div className='section' id='statusSection'>

          <div id='powerWrapper'>
            <PowerButton
              title="دستگاه"
              icon={powerIcon}
              label={powerStatus}
              onChange={(isChecked)=>{setPowerStatus(isChecked ? PowerStatus.ON : PowerStatus.OFF)}}/>
          </div>

          <div id='powerWrapper'>
            <PowerButton
              title="چرخش"
              icon={arrowIcon}
              label={oscillateStatus}
              onChange={(isChecked)=>{setOscillateStatus(isChecked ? OscillateStatus.ON : OscillateStatus.OFF)}}/>
          </div>
          
        </div>

        {/* speed */}
        <div className='section' id='speedSection'>
          <SpeedController
            status={speedStatus}
            onChange={(speed)=>{setSpeedStatus(speed)}}/>
        </div>

        {/* condition */}
        <div className='section' id='conditionWrapper'>

        </div>

      </div>
    </div>
  );
}

export default App;