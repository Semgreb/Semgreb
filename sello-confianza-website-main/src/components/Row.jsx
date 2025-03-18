import React from "react";

function Row({ children }) {
  return (
    <div className="grid gap-4 grid-cols-responsive items-start">
      {children}
    </div>
  );
}

export default React.memo(Row);
