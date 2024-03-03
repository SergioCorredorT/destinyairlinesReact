import React, { useId } from 'react';
import styles from "./TabPanel.module.css";

interface TabProps {
  title: string;
  children: React.ReactNode;
}

interface TabPanelProps {
  children: TabProps[];
}

export function TabPanel({ children }: TabPanelProps) {
  return (
    <div className={styles.tabs}>
      {children.map((tab, index) => {
        const id = useId();
        return (
          <React.Fragment key={id}>
            <input type="radio" name="tabs" id={id} defaultChecked={index === 0} />
            <label htmlFor={id}>{tab.title}</label>
            <div className={styles.tabContainer}>{tab.children}</div>
          </React.Fragment>
        );
      })}
    </div>
  );
}
