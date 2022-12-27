import React from 'react'
import Switch from '@mui/material/Switch'
import './powerButton.scss'

type ButtonProps = {
  title: string,
  icon: string,
  label: string,
  onChange: (isChecked: boolean) => void
}

export default function PowerButton({ title, icon, label, onChange }: ButtonProps){


  const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    onChange(event.target.checked)
  };


  return (
    <div className='powerButton'>
      <header id='header'>
        <span id='icon'><img id="iconImage" src={icon}/></span>
        <a id='title'>{title}</a>
      </header>
      <div id='content'>
        <a id='statusLabel'>{label}</a>
        <Switch onChange={handleChange}/>
      </div>
    </div>
  );
}