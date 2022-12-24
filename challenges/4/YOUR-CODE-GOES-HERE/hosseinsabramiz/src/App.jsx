import { useEffect, useState } from "react";
import { Col, Form, Row } from "react-bootstrap";
import { BsWind , BsWater ,BsSunFill , BsTreeFill, BsPower, BsCode} from "react-icons/bs";
import AOS from "aos";

import "./assets/scss/index.scss";
import 'aos/dist/aos.css';

function App() {

  const rotateControll = [
    {
      id:1,
      icon:<BsWind />,
      title : "ساده",
      speed:"linear"
    },
    {
      id:2,
      icon:<BsWater />,
      title : "اقیانوسی",
      speed:"ease-in-out"
    },
    {
      id:3,
      icon:<BsSunFill />,
      title : "استوایی",
      speed:"ease"
    },
    {
      id:4,
      icon:<BsTreeFill />,
      title : "جنگل",
      speed:"cubic-bezier(0.5,0.36,0.55,0.84)"
    },
  ]

  const [TurnOnFan, setTurnOnFan] = useState(false);
  const [TurnOnRotation, setTurnOnRotation] = useState(false);
  const [FanSpeedControll, setFanSpeedControll] = useState(1)
  const [RotateFanMode, setRotateFanMode] = useState(rotateControll[0]);

  useEffect(()=>{
    AOS.init();
  },[])


  return (
    <>

      <div className="dashBoardLayout">

        <div className="mainShowFanBox">
            <div className={`showMainFanImage${TurnOnFan ? " active" : ""}${TurnOnFan && TurnOnRotation ? " rotation" : ""}`} >
              <div className="showFanImageBox"
                style={{
                  animationTimingFunction:RotateFanMode.speed
                }}
              >
                <img src="/assets/images/blades.svg" alt="" 
                  style={{
                    animationDuration:`${FanSpeedControll}s`
                  }}

                />
              </div>
            </div>
            <div className="ShowFanBoxWave">
              <img src="/assets/images/wave3.png" alt="" />
            </div>
        </div>


        <Row style={{margin:"20px 0"}}>
          <Col>
            <div className="controllItemCard" data-aos="fade-up">
              <div className="controllItemCardHeader">
                <BsPower className="controllItemCardHeaderIcon" />
                <div>دستگاه</div>
              </div>
              <div className="controllItemCardBody">
                <Form.Check 
                  type="switch"
                  id="custom-switch"
                  label={TurnOnFan ? "روشن" : "خاموش"}
                  value={TurnOnFan}
                  onChange={() => setTurnOnFan(!TurnOnFan)}
                />
              </div>
            </div>
          </Col>
          <Col>

            <div className="controllItemCard" data-aos="fade-up" data-aos-delay="200">
              <div className="controllItemCardHeader">
                <BsCode className="controllItemCardHeaderIcon" />
                <div>چرخش</div>
              </div>
              <div className="controllItemCardBody">
                <Form.Check 
                  type="switch"
                  id="custom-switch"
                  label={TurnOnRotation ? "فعال" : "غیرفعال"}
                  value={TurnOnRotation}
                  onChange={() => setTurnOnRotation(!TurnOnRotation)}
                />
              </div>
            </div>

          </Col>
        </Row>
        
        <div className="siteTempBox">
          <div className="siteTempTitle">سرعت چرخش</div>
          <Form.Range 
            value={FanSpeedControll}
            step={.1}
            min={0}
            max={2}
            onChange={(e) => setFanSpeedControll(e.target.value)}
            
          />
        </div>

        <Row style={{margin : "20px 0"}}>
          <div className="siteTempTitle">حالت باد</div>

          {rotateControll.map((item,index) =>
            <Col xs={3} data-aos="fade-left" data-aos-delay={index * 50}>
                <div className={`rotateControllCard${RotateFanMode.id === item.id ? " active" : ""}`} onClick={() => setRotateFanMode(item)}
                  
                >
                  <div className="rotateControllCardIcon">{item.icon}</div>
                  <div className="rotateControllCardTitle">{item.title}</div>
                </div>
            </Col>  
          )}

        </Row>

      </div>





    </>
  );
}

export default App;
