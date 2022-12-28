import React from 'react'
import './fan.scss'

type FanProps = {
  isOn: boolean,
  isOscillating: boolean,
  speedStatus: number,
  conditionStatus: string
}

export default function Fan({ isOn, isOscillating, speedStatus, conditionStatus }: FanProps){

  //render:
  return (
    <div className={'fan' + (isOn ? ' isOn' : '')}>
        
      {/* fan */}
      <div id='fanWrapper'>

        {/* body */}
        <div id='fanContainer' className={(isOscillating ? 'oscillating ' : '') + 'speed_' + speedStatus}>
          <div id='fan-body' className={' condition_' + conditionStatus}>
            <div id='grid-layer-1'>
              <div id='grid-layer-2'>
                <div id='grid-layer-3'>
                  <div id='fan-cap'/>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* waves */}
      <div id='wave_3c859f'>
        <span id='stand'/>
      </div>
      <div id='wave_7bdcb5'/>
      <div id='wave_f6f6f6'/>
    </div>
  );
}