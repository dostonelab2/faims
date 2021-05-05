var app = new Vue({
    el: '#pr-app',
    data: {
      message: 'Hello Vue!',
      counter: 0
    },
    methods: {
        greet: function (event) {
          // `this` inside methods points to the Vue instance
          console.log('hello');
          // `event` is the native DOM event
          if (event) {
            alert(event.target.tagName)
          }
        }
      }
  })