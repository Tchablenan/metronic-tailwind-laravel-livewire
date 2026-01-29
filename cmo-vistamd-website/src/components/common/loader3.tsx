// src/components/common/Loader.tsx
import { useEffect, useState } from 'react'
import logoImage from '@/assets/logo-cmo-vistamd.jpg'
import type { LoaderProps } from '@/types/common'

const Loader = ({ onComplete, duration = 3000 }: LoaderProps) => {
  const [progress, setProgress] = useState(0)
  const [isExiting, setIsExiting] = useState(false)

  useEffect(() => {
    const interval = setInterval(() => {
      setProgress(prev => {
        if (prev >= 100) {
          clearInterval(interval)
          return 100
        }
        return prev + (100 / (duration / 50))
      })
    }, 50)

    const exitTimer = setTimeout(() => {
      setIsExiting(true)
    }, duration)

    const completeTimer = setTimeout(() => {
      onComplete?.()
    }, duration + 600)

    return () => {
      clearInterval(interval)
      clearTimeout(exitTimer)
      clearTimeout(completeTimer)
    }
  }, [duration, onComplete])

  return (
    <div style={{
      position: 'fixed',
      top: 0,
      left: 0,
      right: 0,
      bottom: 0,
      background: 'linear-gradient(135deg, #FFFFFFFF 0%, #FFFFFFFF 100%)',
      display: 'flex',
      flexDirection: 'column',
      alignItems: 'center',
      justifyContent: 'center',
      zIndex: 9999,
      opacity: isExiting ? 0 : 1,
      transform: isExiting ? 'scale(1.1)' : 'scale(1)',
      transition: 'opacity 0.6s ease, transform 0.6s ease',
      pointerEvents: isExiting ? 'none' : 'auto',
      overflow: 'hidden'
    }}>
      {/* Effet de gradient animé en arrière-plan */}
      <div style={{
        position: 'absolute',
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
        background: 'linear-gradient(45deg, rgba(34, 197, 94, 0.05), rgba(16, 117, 185, 0.05), rgba(34, 197, 94, 0.05))',
        backgroundSize: '400% 400%',
        animation: 'gradient-shift 8s ease infinite',
        pointerEvents: 'none'
      }} />

      {/* Particules de fond */}
      <div style={{
        position: 'absolute',
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
        overflow: 'hidden',
        opacity: 0.3
      }}>
        {[...Array(20)].map((_, i) => (
          <div
            key={i}
            style={{
              position: 'absolute',
              width: `${Math.random() * 4 + 2}px`,
              height: `${Math.random() * 4 + 2}px`,
              background: i % 2 === 0 ? '#22c55e' : '#1075B9',
              borderRadius: '50%',
              top: `${Math.random() * 100}%`,
              left: `${Math.random() * 100}%`,
              animation: `particle-float ${5 + Math.random() * 5}s ease-in-out infinite ${Math.random() * 3}s`,
              opacity: 0.6,
              boxShadow: i % 3 === 0 ? '0 0 20px rgba(34, 197, 94, 0.8)' : '0 0 20px rgba(16, 117, 185, 0.8)'
            }}
          />
        ))}
      </div>

      {/* Aura de lumière animée */}
      <div style={{
        position: 'absolute',
        width: '400px',
        height: '400px',
        background: 'radial-gradient(circle, rgba(34, 197, 94, 0.15) 0%, transparent 70%)',
        borderRadius: '50%',
        animation: 'aura-pulse 3s ease-in-out infinite',
        filter: 'blur(40px)',
        pointerEvents: 'none'
      }} />

      {/* Container principal */}
      <div style={{
        position: 'relative',
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        gap: '3rem',
        zIndex: 1
      }}>
        {/* Logo avec animation */}
        <div style={{
          position: 'relative',
          width: '150px',
          height: '150px',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          animation: 'slide-down 0.8s ease-out 0.2s both, float-logo 3s ease-in-out 1s infinite'
        }}>
          {/* Glow layers multiples */}
          <div style={{
            position: 'absolute',
            width: '100%',
            height: '100%',
            borderRadius: '50%',
            background: 'radial-gradient(circle, rgba(34, 197, 94, 0.4), transparent)',
            animation: 'glow-expand 2s ease-in-out infinite',
            filter: 'blur(20px)'
          }} />
          
          <div style={{
            position: 'absolute',
            width: '80%',
            height: '80%',
            borderRadius: '50%',
            background: 'radial-gradient(circle, rgba(16, 117, 185, 0.3), transparent)',
            animation: 'glow-expand-reverse 2.5s ease-in-out infinite',
            filter: 'blur(15px)'
          }} />

          {/* Cercles animés autour du logo */}
          <div style={{
            position: 'absolute',
            width: '100%',
            height: '100%',
            border: '3px solid transparent',
            borderTopColor: '#22c55e',
            borderRightColor: '#22c55e',
            borderRadius: '50%',
            animation: 'rotate 2s linear infinite'
          }}></div>
          <div style={{
            position: 'absolute',
            width: '120%',
            height: '120%',
            border: '3px solid transparent',
            borderBottomColor: '#1075B9',
            borderLeftColor: '#1075B9',
            borderRadius: '50%',
            animation: 'rotate-reverse 3s linear infinite'
          }}></div>

          {/* Cercle scintillant externe */}
          <div style={{
            position: 'absolute',
            width: '140%',
            height: '140%',
            border: '2px solid transparent',
            borderTopColor: 'rgba(34, 197, 94, 0.5)',
            borderRightColor: 'rgba(34, 197, 94, 0.3)',
            borderRadius: '50%',
            animation: 'rotate 4s linear infinite, pulse-opacity 2s ease-in-out infinite'
          }}></div>

          {/* Logo CMO VISTAMD */}
          <div style={{
            width: '100px',
            height: '100px',
            background: 'linear-gradient(135deg, #FFFFFFFF 0%, #FAFAFAFF 100%)',
            borderRadius: '20px',
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            justifyContent: 'center',
            boxShadow: '0 20px 60px rgba(34, 197, 94, 0.4), 0 0 40px rgba(34, 197, 94, 0.3)',
            animation: 'logo-pulse 2s ease-in-out infinite, logo-rotate 6s ease-in-out infinite',
            perspective: '1000px'
          }}>
            <img 
              src={logoImage} 
              alt="CMO VISTAMD Logo"
              style={{
                width: '100px',
                height: '100px',
                objectFit: 'contain',
                filter: 'drop-shadow(0 20px 60px rgba(34, 197, 94, 0.4))',
                animation: 'logo-pulse 2s ease-in-out infinite'
              }}
            />
          </div>

          {/* Croix médicale animée */}
          <div style={{
            position: 'absolute',
            bottom: '-10px',
            right: '-10px',
            width: '40px',
            height: '40px',
            background: '#ef4444',
            borderRadius: '50%',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            boxShadow: '0 10px 30px rgba(239, 68, 68, 0.5), 0 0 30px rgba(239, 68, 68, 0.4)',
            animation: 'heartbeat 1.5s ease-in-out infinite, pulse-scale 2s ease-in-out infinite, rotate-mini 3s ease-in-out infinite'
          }}>
            <div style={{
              position: 'relative',
              width: '20px',
              height: '20px'
            }}>
              <div style={{
                position: 'absolute',
                top: '50%',
                left: '50%',
                width: '3px',
                height: '100%',
                background: 'white',
                transform: 'translate(-50%, -50%)',
                boxShadow: '0 0 10px rgba(255, 255, 255, 0.8)'
              }}></div>
              <div style={{
                position: 'absolute',
                top: '50%',
                left: '50%',
                width: '100%',
                height: '3px',
                background: 'white',
                transform: 'translate(-50%, -50%)',
                boxShadow: '0 0 10px rgba(255, 255, 255, 0.8)'
              }}></div>
            </div>
          </div>
        </div>

        {/* Texte de chargement avec effets avancés */}
        <div style={{
          textAlign: 'center',
          animation: 'fade-in-up 1s ease-out 0.5s both'
        }}>
          <h2 style={{
            fontSize: 'clamp(1.5rem, 4vw, 2rem)',
            fontWeight: '700',
            color: '#04067BFF',
            marginBottom: '0.5rem',
            animation: 'text-glow 2s ease-in-out infinite, slide-in-left 0.8s ease-out 0.6s both',
            letterSpacing: '1px',
            perspective: '1000px'
          }}>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '0.6s', display: 'inline-block', transformStyle: 'preserve-3d' }}>B</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '0.65s', display: 'inline-block', transformStyle: 'preserve-3d' }}>i</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '0.7s', display: 'inline-block', transformStyle: 'preserve-3d' }}>e</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '0.75s', display: 'inline-block', transformStyle: 'preserve-3d' }}>n</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '0.8s', display: 'inline-block', transformStyle: 'preserve-3d' }}>v</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '0.85s', display: 'inline-block', transformStyle: 'preserve-3d' }}>e</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '0.9s', display: 'inline-block', transformStyle: 'preserve-3d' }}>n</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '0.95s', display: 'inline-block', transformStyle: 'preserve-3d' }}>u</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1s', display: 'inline-block', transformStyle: 'preserve-3d' }}>e</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.05s', display: 'inline-block', transformStyle: 'preserve-3d' }}> </span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.1s', display: 'inline-block', transformStyle: 'preserve-3d' }}>c</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.15s', display: 'inline-block', transformStyle: 'preserve-3d' }}>h</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.2s', display: 'inline-block', transformStyle: 'preserve-3d' }}>e</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.25s', display: 'inline-block', transformStyle: 'preserve-3d' }}>z</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.3s', display: 'inline-block', transformStyle: 'preserve-3d' }}> </span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.35s', display: 'inline-block', transformStyle: 'preserve-3d' }}>C</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.4s', display: 'inline-block', transformStyle: 'preserve-3d' }}>M</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.45s', display: 'inline-block', transformStyle: 'preserve-3d' }}>O</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.5s', display: 'inline-block', transformStyle: 'preserve-3d' }}> </span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.55s', display: 'inline-block', transformStyle: 'preserve-3d' }}>&</span>
            <br/>
            <span style={{
              background: 'linear-gradient(135deg, #0E9C1FFF 50%, #1075B9 50%)',
              backgroundSize: '200% 200%',
              animation: 'gradient-animate 4s ease infinite',
              WebkitBackgroundClip: 'text',
              WebkitTextFillColor: 'transparent',
              backgroundClip: 'text',
              fontWeight: 'black',
              display: 'inline-block',
              perspective: '1000px'
            }}>
              <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop-gradient 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.65s', display: 'inline-block', transformStyle: 'preserve-3d' }}>V</span>
              <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop-gradient 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.7s', display: 'inline-block', transformStyle: 'preserve-3d' }}>I</span>
              <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop-gradient 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.75s', display: 'inline-block', transformStyle: 'preserve-3d' }}>S</span>
              <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop-gradient 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.8s', display: 'inline-block', transformStyle: 'preserve-3d' }}>T</span>
              <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop-gradient 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.85s', display: 'inline-block', transformStyle: 'preserve-3d' }}>A</span>
              <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop-gradient 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.9s', display: 'inline-block', transformStyle: 'preserve-3d' }}>M</span>
              <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop-gradient 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.95s', display: 'inline-block', transformStyle: 'preserve-3d' }}>D</span>
            </span>
          </h2>
          <p style={{
            fontSize: 'clamp(0.9rem, 2vw, 1rem)',
            color: '#000000FF',
            fontWeight: '500',
            letterSpacing: '0.5px',
            animation: 'fade-in 0.8s ease-out 2.2s both, subtle-float 2s ease-in-out 2.2s infinite',
            textShadow: '0 2px 10px rgba(34, 197, 94, 0.15)'
          }}>
            Services Médicaux d'Excellence
          </p>
        </div>

        {/* Barre de progression */}
        <div style={{
          width: 'clamp(250px, 80vw, 400px)',
          height: '6px',
          background: 'rgba(148, 163, 184, 0.2)',
          borderRadius: '10px',
          overflow: 'hidden',
          position: 'relative',
          animation: 'fade-in-up 0.8s ease-out 2s both',
          boxShadow: '0 0 20px rgba(34, 197, 94, 0.2)'
        }}>
          <div style={{
            position: 'absolute',
            top: 0,
            left: 0,
            height: '100%',
            width: `${progress}%`,
            background: 'linear-gradient(90deg, #22c55e 0%, #10b981 50%, #1075B9 100%)',
            backgroundSize: '200% 200%',
            animation: 'progress-gradient 1s ease infinite',
            borderRadius: '10px',
            transition: 'width 0.1s linear',
            boxShadow: '0 0 20px rgba(34, 197, 94, 0.6), inset 0 0 10px rgba(255, 255, 255, 0.3)'
          }}>
            <div style={{
              position: 'absolute',
              top: 0,
              left: 0,
              right: 0,
              bottom: 0,
              background: 'linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent)',
              animation: 'shimmer 1.5s infinite'
            }}></div>
          </div>
        </div>

        {/* Pourcentage avec glow */}
        <div style={{
          fontSize: '1.25rem',
          fontWeight: '700',
          color: '#22c55e',
          fontFamily: 'monospace',
          letterSpacing: '2px',
          animation: 'fade-in-up 0.8s ease-out 2.2s both, pulse-glow 1.5s ease-in-out infinite 2.2s, number-scale 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) infinite',
          textShadow: '0 0 15px rgba(34, 197, 94, 0.6), 0 0 30px rgba(34, 197, 94, 0.3)'
        }}>
          {Math.round(progress)}%
        </div>

        {/* Points de chargement animés */}
        <div style={{
          display: 'flex',
          gap: '0.5rem',
          alignItems: 'center',
          animation: 'fade-in-up 0.8s ease-out 2.4s both'
        }}>
          {[0, 1, 2].map((i) => (
            <div
              key={i}
              style={{
                width: '12px',
                height: '12px',
                background: '#22c55e',
                borderRadius: '50%',
                animation: `bounce 1.4s ease-in-out infinite ${i * 0.2}s, dot-glow 1.4s ease-in-out infinite ${i * 0.2}s`,
                boxShadow: '0 0 10px rgba(34, 197, 94, 0.6)'
              }}
            />
          ))}
        </div>
      </div>

      <style>{`
        @keyframes rotate {
          from { transform: rotate(0deg); }
          to { transform: rotate(360deg); }
        }

        @keyframes rotate-reverse {
          from { transform: rotate(360deg); }
          to { transform: rotate(0deg); }
        }

        @keyframes rotate-mini {
          0%, 100% { transform: rotate(0deg); }
          50% { transform: rotate(5deg); }
        }

        @keyframes logo-pulse {
          0%, 100% {
            transform: scale(1);
            box-shadow: 0 20px 60px rgba(34, 197, 94, 0.4), 0 0 40px rgba(34, 197, 94, 0.3);
          }
          50% {
            transform: scale(1.05);
            box-shadow: 0 25px 70px rgba(34, 197, 94, 0.6), 0 0 50px rgba(34, 197, 94, 0.5);
          }
        }

        @keyframes logo-rotate {
          0% { transform: rotateY(0deg); }
          50% { transform: rotateY(5deg); }
          100% { transform: rotateY(0deg); }
        }

        @keyframes float-logo {
          0%, 100% { transform: translateY(0px); }
          50% { transform: translateY(-10px); }
        }

        @keyframes heartbeat {
          0%, 100% { transform: scale(1); }
          10%, 30% { transform: scale(1.1); }
          20%, 40% { transform: scale(1); }
        }

        @keyframes pulse-scale {
          0%, 100% {
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.5), 0 0 30px rgba(239, 68, 68, 0.4);
          }
          50% {
            box-shadow: 0 10px 50px rgba(239, 68, 68, 0.8), 0 0 50px rgba(239, 68, 68, 0.6);
          }
        }

        @keyframes pulse-opacity {
          0%, 100% { opacity: 0.5; }
          50% { opacity: 1; }
        }

        @keyframes text-glow {
          0%, 100% {
            text-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
          }
          50% {
            text-shadow: 0 0 20px rgba(34, 197, 94, 0.8), 0 0 30px rgba(34, 197, 94, 0.6);
          }
        }

        @keyframes glow-expand {
          0%, 100% {
            transform: scale(1);
            opacity: 0.6;
          }
          50% {
            transform: scale(1.2);
            opacity: 0.3;
          }
        }

        @keyframes glow-expand-reverse {
          0%, 100% {
            transform: scale(1.2);
            opacity: 0.3;
          }
          50% {
            transform: scale(1);
            opacity: 0.6;
          }
        }

        @keyframes aura-pulse {
          0%, 100% {
            transform: scale(1);
            opacity: 0.8;
          }
          50% {
            transform: scale(1.2);
            opacity: 0.4;
          }
        }

        @keyframes gradient-shift {
          0% { background-position: 0% 50%; }
          50% { background-position: 100% 50%; }
          100% { background-position: 0% 50%; }
        }

        @keyframes gradient-animate {
          0%, 100% { background-position: 0% 50%; }
          50% { background-position: 100% 50%; }
        }

        @keyframes progress-gradient {
          0%, 100% { background-position: 0% 50%; }
          50% { background-position: 100% 50%; }
        }

        @keyframes shimmer {
          0% { transform: translateX(-100%); }
          100% { transform: translateX(200%); }
        }

        @keyframes bounce {
          0%, 80%, 100% {
            transform: scale(0);
            opacity: 0.5;
          }
          40% {
            transform: scale(1);
            opacity: 1;
          }
        }

        @keyframes dot-glow {
          0%, 100% {
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.6);
          }
          50% {
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.8);
          }
        }

        @keyframes particle-float {
          0%, 100% {
            transform: translateY(0) translateX(0);
            opacity: 0.6;
          }
          25% { opacity: 1; }
          50% {
            transform: translateY(-30px) translateX(20px);
            opacity: 0.6;
          }
          75% { opacity: 1; }
        }

        @keyframes char-appear {
          from {
            opacity: 0;
            transform: translateY(20px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        @keyframes char-pop {
          0% {
            opacity: 0;
            transform: scale(0) translateY(20px) rotateX(-90deg);
          }
          50% {
            transform: scale(1.15) translateY(-5px) rotateX(0deg);
          }
          100% {
            opacity: 1;
            transform: scale(1) translateY(0) rotateX(0deg);
          }
        }

        @keyframes char-pop-gradient {
          0% {
            opacity: 0;
            transform: scale(0) translateY(20px) rotateX(-90deg);
          }
          50% {
            transform: scale(1.2) translateY(-5px) rotateX(0deg);
          }
          100% {
            opacity: 1;
            transform: scale(1) translateY(0) rotateX(0deg);
          }
        }

        @keyframes slide-down {
          from {
            opacity: 0;
            transform: translateY(-30px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        @keyframes slide-in-left {
          from {
            opacity: 0;
            transform: translateX(-30px);
          }
          to {
            opacity: 1;
            transform: translateX(0);
          }
        }

        @keyframes fade-in-up {
          from {
            opacity: 0;
            transform: translateY(20px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        @keyframes fade-in {
          from { opacity: 0; }
          to { opacity: 1; }
        }

        @keyframes subtle-float {
          0%, 100% { transform: translateY(0px); }
          50% { transform: translateY(-3px); }
        }

        @keyframes pulse-glow {
          0%, 100% {
            text-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
            color: #22c55e;
          }
          50% {
            text-shadow: 0 0 20px rgba(34, 197, 94, 0.8);
            color: #10b981;
          }
        }

        @keyframes number-scale {
          0%, 100% { transform: scale(1); }
          50% { transform: scale(1.1); }
        }
      `}</style>
    </div>
  )
}

export default Loader