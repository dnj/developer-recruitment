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

  //dir
  document.dir = 'rtl';

  //states:
  const cookies = new Cookies();
  const cookiesMaxAge = 1000*24*60*60;
  const [powerStatus    , setPowerStatus    ] = useState(cookies.get('PowerStatus'    ) || PowerStatus.OFF       );
  const [oscillateStatus, setOscillateStatus] = useState(cookies.get('OscillateStatus') || OscillateStatus.OFF   );
  const [speedStatus    , setSpeedStatus    ] = useState(cookies.get('SpeedStatus'    ) || SpeedStatus.MIDDLE    );
  const [conditionStatus, setConditionStatus] = useState(cookies.get('ConditionStatus') || ConditionStatus.NORMAL);

  //effects:
  useEffect(() => { cookies.set('PowerStatus'    , powerStatus     , { path: '/', maxAge: cookiesMaxAge }); }, [powerStatus    ]);
  useEffect(() => { cookies.set('OscillateStatus', oscillateStatus , { path: '/', maxAge: cookiesMaxAge }); }, [oscillateStatus]);
  useEffect(() => { cookies.set('SpeedStatus'    , speedStatus     , { path: '/', maxAge: cookiesMaxAge }); }, [speedStatus    ]);
  useEffect(() => { cookies.set('ConditionStatus', conditionStatus , { path: '/', maxAge: cookiesMaxAge }); }, [conditionStatus]);
  
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

        <div id='statusSection'>

          {/* power */}
          <div id='powerWrapper'>
            <PowerButton
              title="دستگاه"
              isChecked={powerStatus === PowerStatus.ON}
              icon={powerIcon}
              label={powerStatus}
              onChange={(isChecked)=>{setPowerStatus(isChecked ? PowerStatus.ON : PowerStatus.OFF)}}/>
          </div>

          {/* oscillate */}
          <div id='powerWrapper'>
            <PowerButton
              title="چرخش"
              isChecked={oscillateStatus === OscillateStatus.ON}
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