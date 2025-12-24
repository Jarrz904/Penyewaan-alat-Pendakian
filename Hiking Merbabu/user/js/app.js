particlesJS.load('particles-js', 'js/particles.json', function() {
  console.log('particles.js loaded!');
});
particlesJS("particles-js", {
  particles: {
    number: { value: 60 },
    size: { value: 3 },
    move: { speed: 1.2 },
    line_linked: {
      enable: true,
      opacity: 0.3
    },
    color: {
      value: "#ffffff"
    }
  },
  interactivity: {
    events: {
      onhover: {
        enable: true,
        mode: "repulse"
      }
    }
  }
});
