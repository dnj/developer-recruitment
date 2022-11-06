import styles from '../styles/Clock.module.css';
import { useEffect, useRef } from 'react';
import PropTypes from 'prop-types';

export default function Clock(props) {
    const firstHour = props.activeHours[0];
    const secondHour = props.activeHours[1];
    const canvas1Ref = useRef(null);
    const canvas2Ref = useRef(null);

    useEffect(() => {
        if ((firstHour.start > firstHour.end) || (secondHour.start > secondHour.end)) {
            throw new Error('Start time cannot be greater than end time');
        } else {  
            const canvas = canvas1Ref.current
            const context = canvas.getContext('2d')
            context.beginPath()
            context.arc(100, 100, 100, (firstHour.start* 2* Math.PI/24) - Math.PI/2, (firstHour.end* 2 * Math.PI/24)- Math.PI/2 , false);
            context.stroke()
            context.lineWidth = 2;
            context.strokeStyle = 'orange';
            
            const canvas2 = canvas2Ref.current
            const context2 = canvas2.getContext('2d')
            context2.beginPath()
            context2.arc(100, 100, 100, (secondHour.start* 2* Math.PI/24) - Math.PI/2, (secondHour.end* 2 * Math.PI/24)- Math.PI/2 , false);
            context2.stroke()
            context2.lineWidth = 2;
            context2.strokeStyle = 'blue';
        }
}, [props.activeHours])

    
 

    return (
        <div>
            <div className={styles.clock}>
                <div><canvas ref={canvas1Ref} style={{position: 'absolute'}} height={200}/></div>
                <div><canvas ref={canvas2Ref} style={{position: 'absolute'}} height={200}/></div>
            </div>
        </div>
    )
}


Clock.propTypes = {
    activeHours: PropTypes.arrayOf(
        PropTypes.shape({
            start: PropTypes.number.isRequired,
            start: PropTypes.oneOf([0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24]).isRequired,
            end: PropTypes.number.isRequired,
            end: PropTypes.oneOf([0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24]).isRequired,
        })
    ).isRequired,
};
