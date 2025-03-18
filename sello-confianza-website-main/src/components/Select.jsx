import React from "react";

function Select() {
  const handlerOnChange = () => {
    // console.log("Select.handlerOnChange:", event.target.value);
  };

  return (
    <div>
      <select
        className="inline-flex justify-between border border-gray-100 w-full p-3"
        onChange={handlerOnChange}
      >
        <option className="px-6" value={25}>
          Mostrqar 25
        </option>
        <option className="px-6" value={50}>
          Mostrar 50
        </option>
        <option className="px-6" value={100}>
          Mostrar 100
        </option>
      </select>
    </div>
  );
}

export default Select;
