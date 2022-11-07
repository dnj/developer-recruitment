import Clock from "../components/Clock";
import { render } from "@testing-library/react";


test("Clock renders with active hours", () => {
    const clock = render(<Clock activeHours={[{start: 5, end: 13}, {start: 22, end: 24}]}/>);
    expect(clock).toBeTruthy();
    }
);

test("throws errors with out of range numbers", () => {
    expect(() => render(<Clock activeHours={[{start: 5, end: 13}, {start: 22, end: 25}]}/>)).toThrow();
    expect(() => render(<Clock activeHours={[{start: 5, end: 13}, {start: 22, end: 0}]}/>)).toThrow();
    expect(() => render(<Clock activeHours={[{start: 5, end: 13}, {start: 22, end: -1}]}/>)).toThrow();
    expect(() => render(<Clock activeHours={[{start: 5, end: 13}, {start: 22, end: 100}]}/>)).toThrow();
    expect(() => render(<Clock activeHours={[{start: 5, end: 13}, {start: 22, end: 24.5}]}/>)).toThrow();
});
