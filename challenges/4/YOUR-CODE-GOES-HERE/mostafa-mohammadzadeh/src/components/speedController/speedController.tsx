import React from 'react'
import Slider from '@mui/material/Slider'
import './speedController.scss'

type SpeedControllerProps = {
  status: number,
  onChange: (value: number) => void
}

export default function SpeedController({ status, onChange }: SpeedControllerProps){


  // const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
  //   onChange(event.target.checked)
  // };

  const labels = [
    'کند',
    'آهسته',
    'متوسط',
    'سریع',
    'کولاک'
  ]


  const handleChange = (event: Event, newValue: number | number[]) => {
    onChange(newValue as number);
  };

  return (
    <div className='speedController'>
      <header id='header'>
        <a id='title'>سرعت چرخش</a>
      </header>
      <div id='content'>
        <Slider
          value={status}
          valueLabelDisplay="on"
          valueLabelFormat={value => <div className="label">{labels[value]}</div>}
          step={1}
          min={0}
          max={4}
          onChange={handleChange}
        />
      </div>
    </div>
  );
}