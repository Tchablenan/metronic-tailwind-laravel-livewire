// src/App.tsx
import { useEffect } from 'react'
import Home from '@pages/Home'
import './assets/css/custom.css'
import './App.css'




function App() {
  useEffect(() => {
    // Charger les styles du template
    const loadStyles = () => {
      const links = [
        '/assets/css/plugins/plugins.css',
        '/assets/css/plugins/magnifying-popup.css',
        '/assets/css/vendor/bootstrap.min.css',
        '/assets/css/style.css'
      ];

      links.forEach(href => {
        if (!document.querySelector(`link[href="${href}"]`)) {
          const link = document.createElement('link');
          link.rel = 'stylesheet';
          link.href = href;
          document.head.appendChild(link);
        }
      });
    };

    loadStyles();
  }, []);

  return (
    <>

     
      <Home />
    </>
  )
}

export default App