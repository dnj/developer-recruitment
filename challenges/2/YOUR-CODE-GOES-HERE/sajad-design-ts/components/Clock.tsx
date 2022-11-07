import { useEffect, useRef } from 'react';
import { IProps } from '../types';
import styles from '../styles/Clock.module.css';


export default function Clock(props: IProps) {    
    type numberRange = 0 | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 | 11 | 12 | 13 | 14 | 15 | 16 | 17 | 18 | 19 | 20 | 21 | 22 | 23 | 24;

    interface IHour {
        start: numberRange;
        end: numberRange;
    }
    
    const firstHour : IHour = props.activeHours[0];
    const secondHour : IHour = props.activeHours[1];
    const canvas1Ref = useRef(null);
    const canvas2Ref = useRef(null);

    useEffect(() => {
        if ((firstHour.start > firstHour.end) || (secondHour.start > secondHour.end)) {
            throw new Error('Start time cannot be greater than end time');
        } else if (firstHour.start === firstHour.end) {
            throw new Error('Start time cannot be equal to end time');
        } else if (secondHour.start === secondHour.end) {
            throw new Error('Start time cannot be equal to end time');
        } else if (firstHour.start === secondHour.start) {
            throw new Error('Start time cannot be equal to start time of second hour');
        } else if (firstHour.end === secondHour.end) {
            throw new Error('End time cannot be equal to end time of second hour');
        } else if (firstHour.start === secondHour.end) {
            throw new Error('Start time cannot be equal to end time of second hour');
        } else if (firstHour.end === secondHour.start) {
            throw new Error('End time cannot be equal to start time of second hour');
        } else if (firstHour.start > secondHour.start) {
            throw new Error('Start time of first hour cannot be greater than start time of second hour');
        } else if (firstHour.end > secondHour.end) {
            throw new Error('End time of first hour cannot be greater than end time of second hour');
        } else if (firstHour.start > secondHour.end) {
            throw new Error('Start time of first hour cannot be less than end time of second hour');
        } else if (firstHour.end > secondHour.start) {
            throw new Error('End time of first hour cannot be less than start time of second hour');
        } else if (firstHour.start > secondHour.start) {
            throw new Error('Start time of first hour cannot be greater than start time of second hour');
        } else if (firstHour.end > secondHour.end) {
            throw new Error('End time of first hour cannot be greater than end time of second hour');
        } else if (firstHour.start<0 || firstHour.start>24 || firstHour.end<0 || firstHour.end>24 || secondHour.start<0 || secondHour.start>24 || secondHour.end<0 || secondHour.end>24) {
            throw new Error('Start and end times must be between 0 and 24');
        } else {  
            const canvas : any = canvas1Ref.current
            const context = canvas.getContext('2d')
            context.beginPath()
            context.arc(100, 100, 100, (firstHour.start* 2* Math.PI/24) - Math.PI/2, (firstHour.end* 2 * Math.PI/24)- Math.PI/2 , false);
            context.stroke()
            context.lineWidth = 2;
            context.strokeStyle = 'orange';
            
            const canvas2 : any = canvas2Ref.current
            const context2 = canvas2.getContext('2d')
            context2.beginPath()
            context2.arc(100, 100, 100, (secondHour.start* 2* Math.PI/24) - Math.PI/2, (secondHour.end* 2 * Math.PI/24)- Math.PI/2 , false);
            context2.stroke()
            context2.lineWidth = 2;
            context2.strokeStyle = 'blue';
        }
        }, [props.activeHours]);

    return ( 
            <div className={styles.clock}>
                <div><canvas ref={canvas1Ref} style={{position: 'absolute'}} height={200}/></div>
                <div><canvas ref={canvas2Ref} style={{position: 'absolute'}} height={200}/></div>
            </div>
            )
}
