import "./clock.scss";
import React from "react";
export default function Clock({ activeHours }) {
  return (
    <div id="clock">
      <DisplayActiveHours activeHourse={activeHours} />
      <DisplayHourse />
    </div>
  );
}

function DisplayHourse() {
  function createHourIndicator(i) {
    if ((i + 1) % 6 === 0) {
      return React.createElement(
        "span",
        { className: "hour number", key: i },
        <span>{`0${i + 1 === 24 ? 0 : i + 1}`.slice(-2)}</span>
      );
    } else {
      return React.createElement("span", { className: "hour", key: i });
    }
  }
  function displayHoursContainer() {
    const list = [];
    for (let i = 0; i < 24; i++) {
      list.push(createHourIndicator(i));
    }

    const output = React.createElement(
      "div",
      { className: "hour_container" },
      list
    );

    return output;
  }

  return displayHoursContainer();
}
function DisplayActiveHours({ activeHourse }) {
  return (
    <div className="activeHours_container">
      {activeHourse.map((item, i) => (
        <div
          key={i}
          className="active_container"
          style={{ transform: `rotate(${7.5 + (item.start - 6) * 15}deg)` }}
        >
          {[...new Array(item.end - item.start)].map((ind, i) => (
            <span key={i} className="active_indicator"></span>
          ))}
        </div>
      ))}
    </div>
  );
}
