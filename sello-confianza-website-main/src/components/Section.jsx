import React from "react";

function Section({ children }) {
  return <div className="py-20">{children}</div>;
}

export default React.memo(Section);
