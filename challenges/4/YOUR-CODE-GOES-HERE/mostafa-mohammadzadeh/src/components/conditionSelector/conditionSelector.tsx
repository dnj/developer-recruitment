import React from 'react'
import SelectButton from '../selectButton/selectButton'
import windIcon_fff from '../../assets/wind_fff.svg'
import windIcon_333 from '../../assets/wind_333.svg'
import waterIcon_fff from '../../assets/water_fff.svg'
import waterIcon_333 from '../../assets/water_333.svg'
import sunIcon_fff from '../../assets/sun_fff.svg'
import sunIcon_333 from '../../assets/sun_333.svg'
import treeIcon_fff from '../../assets/tree_fff.svg'
import treeIcon_333 from '../../assets/tree_333.svg'
import './conditionSelector.scss'

type SelectorProps = {
  condition: string,
  onChange: (condition: string) => void
}

export default function ConditionSelector({ condition, onChange}: SelectorProps){

  //render:
  return (
    <div className='conditionSelector'>
      <a id='title'>حالت باد</a>
      <div id='selectButtonsContainer'>
          
          <SelectButton
            isSelected={condition === 'NORMAL'}
            onSelect={()=>{onChange('NORMAL')}}
            title={'ساده'}
            selectedIcon={windIcon_fff}
            notSelectedIcon={windIcon_333}/>

          <SelectButton
            isSelected={condition === 'OCEANIC'}
            onSelect={()=>{onChange('OCEANIC')}}
            title={'اقیانوسی'}
            selectedIcon={waterIcon_fff}
            notSelectedIcon={waterIcon_333}/>

          <SelectButton
            isSelected={condition === 'TROPICAL'}
            onSelect={()=>{onChange('TROPICAL')}}
            title={'استوایی'}
            selectedIcon={sunIcon_fff}
            notSelectedIcon={sunIcon_333}/>

          <SelectButton
            isSelected={condition === 'WOODSY'}
            onSelect={()=>{onChange('WOODSY')}}
            title={'جنگلی'}
            selectedIcon={treeIcon_fff}
            notSelectedIcon={treeIcon_333}/>

      </div>
    </div>
  );
}