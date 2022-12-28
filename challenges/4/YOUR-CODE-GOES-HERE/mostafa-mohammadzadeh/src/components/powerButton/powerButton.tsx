import React from 'react'
import Switch from '@mui/material/Switch'
import { createTheme, ThemeProvider } from '@mui/material/styles'
import rtlPlugin from 'stylis-plugin-rtl'
import { prefixer } from 'stylis'
import { CacheProvider } from '@emotion/react'
import createCache from '@emotion/cache'
import './powerButton.scss'

type ButtonProps = {
  title: string,
  isChecked: boolean,
  icon: string,
  label: string,
  onChange: (isChecked: boolean) => void
}

export default function PowerButton({ title, isChecked, icon, label, onChange }: ButtonProps){

  //handleChange:
  const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    onChange(event.target.checked)
  };

  //theme:
  const theme = createTheme({
    direction: 'rtl',
    status: {
      danger: '#e53e3e',
    },
    palette: {
      secondary: {
        main: '#3c859f',
        darker: '#3c859f',
      },
    },
  });

  //create rtl cache:
  const cacheRtl = createCache({
    key: 'muirtl',
    stylisPlugins: [prefixer, rtlPlugin],
  });

  //render:
  return (
    <CacheProvider value={cacheRtl}>
      <ThemeProvider theme={theme}>
        <div className='powerButton'>
          <header id='header'>
            <span id='icon'><img id="iconImage" src={icon}/></span>
            <a id='title'>{title}</a>
          </header>
          <div id='content'>
            <a id='statusLabel'>{label}</a>
            <Switch
              checked={isChecked}
              color={"secondary"}
              onChange={handleChange}/>
          </div>
        </div>
      </ThemeProvider>
    </CacheProvider>
  );
}