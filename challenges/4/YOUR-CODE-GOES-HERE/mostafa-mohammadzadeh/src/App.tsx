import React, { useEffect, useState } from 'react'
import Cookies from 'universal-cookie'
import PowerButton from './components/powerButton/powerButton'
import SpeedController from './components/speedController/speedController'
import ConditionSelector from './components/conditionSelector/conditionSelector'
import powerIcon from './assets/power.svg'
import arrowIcon from './assets/arrows-left-right.svg'
import Fan from './components/fan/fan'
import './stylesheets/App.scss'

//material ui theme
declare module '@mui/material/styles' {
 
  interface Theme {
    direction: React.CSSProperties['direction'];
  }

  interface Theme {
    status: {
      danger: React.CSSProperties['color'];
    };
  }

  interface PaletteColor {
    darker?: string;
  }

  interface SimplePaletteColorOptions {
    darker?: string;
  }

  interface ThemeOptions {
    status: {
      danger: React.CSSProperties['color'];
    };
  }
}

//enums:
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

enum ConditionStatus {
  NORMAL   = 'NORMAL',
  OCEANIC  = 'OCEANIC',
  TROPICAL = 'TROPICAL',
  WOODSY   = 'WOODSY'
}

function App(){

  //states:
  const [powerStatus    , setPowerStatus    ] = useState(PowerStatus.OFF       );
  const [oscillateStatus, setOscillateStatus] = useState(OscillateStatus.OFF   );
  const [speedStatus    , setSpeedStatus    ] = useState(SpeedStatus.MIDDLE    );
  const [conditionStatus, setConditionStatus] = useState(ConditionStatus.NORMAL);
  
  //render:
  return (
    <div className="App" dir='rtl'>
      <div className='window'>

        {/* fan */}
        <div id='fanSection'>
          <Fan
            isOn={powerStatus === PowerStatus.ON}
            isOscillating={oscillateStatus === OscillateStatus.ON}
            speedStatus={speedStatus}
            conditionStatus={conditionStatus}/>
        </div>

        {/* status */}
        <div id='statusSection'>

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
        <div id='speedSection'>
          <SpeedController
            status={speedStatus}
            onChange={(speed)=>{setSpeedStatus(speed)}}/>
        </div>

        {/* condition */}
        <div id='conditionWrapper'>
          <ConditionSelector
            condition={conditionStatus}
            onChange={(condition)=>{setConditionStatus(condition as ConditionStatus)}}/>
        </div>

      </div>
    </div>
  );
}

export default App;