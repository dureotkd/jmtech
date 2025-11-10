function Loading() {
  return (
    <div className="overlay !flex" id="loadingOverlay">
      <div className="overlay-text">
        <img
          className="w-16"
          src="https://www.jmtech.asia/assets/app_hyup/images/loading.gif"
          alt=""
        />
      </div>
    </div>
  );
}

export default Loading;
