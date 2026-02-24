import React from "react";

function ComponentLoading({ className = "w-16" }) {
  return (
    <img
      className={className}
      src="https://www.jmtech.asia/assets/app_hyup/images/loading.gif"
      alt=""
    />
  );
}

export default ComponentLoading;
