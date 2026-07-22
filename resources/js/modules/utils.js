export const createDragAutoScroller = ({ edgeSize = 120, maxSpeed = 28 } = {}) => {
  let active = false;
  let pointerY = null;
  let animationFrame = null;

  const scrollSpeed = () => {
    if (pointerY === null) return 0;

    if (pointerY < edgeSize) {
      const intensity = Math.min(1, (edgeSize - Math.max(pointerY, 0)) / edgeSize);
      return -Math.max(2, Math.round(maxSpeed * intensity));
    }

    const lowerEdge = window.innerHeight - edgeSize;
    if (pointerY > lowerEdge) {
      const intensity = Math.min(1, (Math.min(pointerY, window.innerHeight) - lowerEdge) / edgeSize);
      return Math.max(2, Math.round(maxSpeed * intensity));
    }

    return 0;
  };

  const tick = () => {
    if (!active) {
      animationFrame = null;
      return;
    }

    const speed = scrollSpeed();
    const scrollingElement = document.scrollingElement || document.documentElement;
    const maxScrollTop = Math.max(0, scrollingElement.scrollHeight - window.innerHeight);
    const canScrollUp = speed < 0 && scrollingElement.scrollTop > 0;
    const canScrollDown = speed > 0 && scrollingElement.scrollTop < maxScrollTop;

    if (canScrollUp || canScrollDown) {
      window.scrollBy(0, speed);
    }

    animationFrame = window.requestAnimationFrame(tick);
  };

  const start = (clientY) => {
    active = true;
    pointerY = clientY;
    if (animationFrame === null) {
      animationFrame = window.requestAnimationFrame(tick);
    }
  };

  const update = (clientY) => {
    if (active) pointerY = clientY;
  };

  const stop = () => {
    active = false;
    pointerY = null;
    if (animationFrame !== null) {
      window.cancelAnimationFrame(animationFrame);
      animationFrame = null;
    }
  };

  return {
    isActive: () => active,
    start,
    stop,
    update,
  };
};
