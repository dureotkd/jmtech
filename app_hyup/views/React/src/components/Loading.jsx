function Loading() {
  return (
    <div className="overlay !flex" id="loadingOverlay">
      <div className="overlay-text">
        <img
          className="w-16"
          src="https://jmtech.test/assets/app_hyup/images/loading.gif"
          alt=""
        />
      </div>
    </div>
  );
}

export default Loading;
