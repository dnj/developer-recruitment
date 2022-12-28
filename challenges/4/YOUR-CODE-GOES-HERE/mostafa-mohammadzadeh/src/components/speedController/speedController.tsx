import React from 'react'
import Slider from '@mui/material/Slider'
import { createTheme, ThemeProvider } from '@mui/material/styles'
import rtlPlugin from 'stylis-plugin-rtl'
import { prefixer } from 'stylis'
import { CacheProvider } from '@emotion/react'
import createCache from '@emotion/cache'
import './speedController.scss'

type SpeedControllerProps = {
  status: number,
  onChange: (value: number) => void
}

export default function SpeedController({ status, onChange }: SpeedControllerProps){

  //labels:
  const labels = [
    'کند',
    'آهسته',
    'متوسط',
    'سریع',
    'کولاک'
  ];

  //handleChange:
  const handleChange = (event: Event, newValue: number | number[]) => {
    onChange(newValue as number);
  };

  //create rtl cache:
  const cacheRtl = createCache({
    key: 'muirtl',
    stylisPlugins: [prefixer, rtlPlugin],
  });

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

  //render:
  return (
    <CacheProvider value={cacheRtl}>
      <ThemeProvider theme={theme}>
        <div className='speedController' dir="rtl">
          <header id='header'>
            <a id='title'>سرعت چرخش</a>
          </header>
          <div id='content'>
            <Slider
              value={status}
              dir="rtl"
              valueLabelDisplay="on"
              valueLabelFormat={value => <div className="label">{labels[value]}</div>}
              step={1}
              min={0}
              max={4}
              color={"secondary"}
              onChange={handleChange}/>
          </div>
        </div>
      </ThemeProvider>
    </CacheProvider>
  );
}