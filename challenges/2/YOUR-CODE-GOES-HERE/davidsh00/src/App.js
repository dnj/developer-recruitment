import "./App.css";
import Clock from "./components/clock/clock";

function App() {
  return (
    <div className="App">
      <Clock
        activeHours={[
          { start: 5, end: 13 },
          { start: 22, end: 24 },
        ]}
      />
    </div>
  );
}

export default App;
