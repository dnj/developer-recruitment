import React from 'react'
import './selectButton.scss'

type ButtonProps = {
  title: string,
  selectedIcon: string,
  notSelectedIcon: string,
  isSelected: boolean,
  onSelect: () => void
}

export default function SelectButton({ title, selectedIcon, notSelectedIcon, isSelected, onSelect }: ButtonProps){

  //render:
  return (
    <button
      className={'selectButton' + (isSelected ? ' isSelected' : '')}
      onClick={()=>onSelect()}>
      <img
        id="iconImage"
        src={isSelected ? selectedIcon : notSelectedIcon}/>
      <a id='title'>{title}</a>
    </button>
  );
}