// src/components/common/Loader.tsx
import { useEffect, useState } from 'react'
import logoImage from '@/assets/logo-cmo-vistamd.jpg'
import type { LoaderProps } from '@/types/common'

const Loader = ({ onComplete, duration = 5000 }: LoaderProps) => {
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
    }, duration + 1200)

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
      transform: isExiting ? 'scale(0.7)' : 'scale(1)',
      filter: isExiting ? 'blur(40px) saturate(0)' : 'blur(0) saturate(1)',
      transition: 'opacity 1.2s ease-out, transform 1.2s ease-out, filter 1.2s ease-out',
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
        {/* Logo avec animation de DISSOLUTION */}
        <div style={{
          position: 'relative',
          width: 'clamp(250px, 50vw, 400px)',
          height: 'clamp(250px, 50vw, 400px)',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          animation: 'float-logo 3s ease-in-out 2s infinite'
        }}>
          {/* Fragments en explosion/implosion autour du logo */}
          {[...Array(30)].map((_, i) => {
            const angle = (i / 30) * 360;
            const distance = 150;
            return (
              <div
                key={i}
                style={{
                  position: 'absolute',
                  width: `${Math.random() * 8 + 4}px`,
                  height: `${Math.random() * 8 + 4}px`,
                  background: i % 3 === 0 ? '#22c55e' : i % 3 === 1 ? '#1075B9' : '#FFFFFF',
                  borderRadius: Math.random() > 0.5 ? '50%' : '0%',
                  top: '50%',
                  left: '50%',
                  transform: 'translate(-50%, -50%)',
                  animation: isExiting 
                    ? `fragment-explode 1.2s cubic-bezier(0.4, 0, 1, 1) forwards`
                    : `dissolve-fragment ${1.5 + (i * 0.02)}s cubic-bezier(0.4, 0, 0.2, 1) forwards`,
                  animationDelay: isExiting ? `${i * 0.01}s` : `${i * 0.015}s`,
                  boxShadow: i % 3 === 0 
                    ? '0 0 10px rgba(34, 197, 94, 0.8)' 
                    : i % 3 === 1 
                      ? '0 0 10px rgba(16, 117, 185, 0.8)' 
                      : '0 0 8px rgba(255, 255, 255, 0.6)',
                  opacity: 0,
                  '--angle': `${angle}deg`,
                  '--distance': `${distance}px`
                } as React.CSSProperties}
              />
            );
          })}
          {/* Glow layers multiples */}
          <div style={{
            position: 'absolute',
            width: '100%',
            height: '100%',
            borderRadius: '50%',
            background: 'radial-gradient(circle, rgba(34, 197, 94, 0.4), transparent)',
            animation: 'glow-expand 2s ease-in-out infinite',
            filter: 'blur(30px)'
          }} />
          
          <div style={{
            position: 'absolute',
            width: '80%',
            height: '80%',
            borderRadius: '50%',
            background: 'radial-gradient(circle, rgba(16, 117, 185, 0.3), transparent)',
            animation: 'glow-expand-reverse 2.5s ease-in-out infinite',
            filter: 'blur(25px)'
          }} />

          {/* Cercles animés autour du logo */}
          <div style={{
            position: 'absolute',
            width: '100%',
            height: '100%',
            border: '4px solid transparent',
            borderTopColor: '#22c55e',
            borderRightColor: '#22c55e',
            borderRadius: '50%',
            animation: 'rotate 2s linear infinite'
          }}></div>
          <div style={{
            position: 'absolute',
            width: '120%',
            height: '120%',
            border: '4px solid transparent',
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
            border: '3px solid transparent',
            borderTopColor: 'rgba(34, 197, 94, 0.5)',
            borderRightColor: 'rgba(34, 197, 94, 0.3)',
            borderRadius: '50%',
            animation: 'rotate 4s linear infinite, pulse-opacity 2s ease-in-out infinite'
          }}></div>

          {/* Logo avec animation de DISSOLUTION */}
          <div style={{
            width: '100%',
            height: '100%',
            background: 'linear-gradient(135deg, #FFFFFFFF 0%, #FAFAFAFF 100%)',
            borderRadius: '30px',
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            justifyContent: 'center',
            boxShadow: '0 20px 60px rgba(34, 197, 94, 0.4), 0 0 40px rgba(34, 197, 94, 0.3)',
            animation: 'logo-dissolve-in 2s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards, logo-breathe 2s ease-in-out 2s infinite',
            perspective: '1000px',
            padding: '20px',
            transformStyle: 'preserve-3d'
          }}>
            <img 
              src={logoImage} 
              alt="CMO VISTAMD Logo"
              style={{
                width: '100%',
                height: '100%',
                objectFit: 'contain',
                filter: 'drop-shadow(0 20px 60px rgba(34, 197, 94, 0.4))',
                animation: 'image-dissolve-zoom 2.2s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards'
              }}
            />
          </div>

          {/* Croix médicale animée avec effet dissolution */}
          <div style={{
            position: 'absolute',
            bottom: '-10px',
            right: '-10px',
            width: '50px',
            height: '50px',
            background: '#ef4444',
            borderRadius: '50%',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            boxShadow: '0 10px 30px rgba(239, 68, 68, 0.5), 0 0 30px rgba(239, 68, 68, 0.4)',
            animation: 'heartbeat 1.5s ease-in-out infinite, pulse-scale 2s ease-in-out infinite, rotate-mini 3s ease-in-out infinite, cross-appear 1s cubic-bezier(0.34, 1.56, 0.64, 1) 1.6s both'
          }}>
            <div style={{
              position: 'relative',
              width: '24px',
              height: '24px'
            }}>
              <div style={{
                position: 'absolute',
                top: '50%',
                left: '50%',
                width: '4px',
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
                height: '4px',
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
          animation: 'fade-in-up 1s ease-out 0.8s both'
        }}>
          <h2 style={{
            fontSize: 'clamp(2rem, 6vw, 3.5rem)',
            fontWeight: '700',
            color: '#04067BFF',
            marginBottom: '0.5rem',
            animation: 'text-glow 2s ease-in-out infinite, slide-in-left 0.8s ease-out 1s both',
            letterSpacing: '1px',
            perspective: '1000px'
          }}>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1s', display: 'inline-block', transformStyle: 'preserve-3d' }}>B</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.05s', display: 'inline-block', transformStyle: 'preserve-3d' }}>i</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.1s', display: 'inline-block', transformStyle: 'preserve-3d' }}>e</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.15s', display: 'inline-block', transformStyle: 'preserve-3d' }}>n</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.2s', display: 'inline-block', transformStyle: 'preserve-3d' }}>v</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.25s', display: 'inline-block', transformStyle: 'preserve-3d' }}>e</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.3s', display: 'inline-block', transformStyle: 'preserve-3d' }}>n</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.35s', display: 'inline-block', transformStyle: 'preserve-3d' }}>u</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.4s', display: 'inline-block', transformStyle: 'preserve-3d' }}>e</span>
            <span style={{ display: 'inline-block', width: '0.5em' }}> </span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.5s', display: 'inline-block', transformStyle: 'preserve-3d' }}>c</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.55s', display: 'inline-block', transformStyle: 'preserve-3d' }}>h</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.6s', display: 'inline-block', transformStyle: 'preserve-3d' }}>e</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.65s', display: 'inline-block', transformStyle: 'preserve-3d' }}>z</span>
            <span style={{ display: 'inline-block', width: '0.5em' }}> </span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.75s', display: 'inline-block', transformStyle: 'preserve-3d' }}>C</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.8s', display: 'inline-block', transformStyle: 'preserve-3d' }}>M</span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.85s', display: 'inline-block', transformStyle: 'preserve-3d' }}>O</span>
            <span style={{ display: 'inline-block', width: '0.5em' }}> </span>
            <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '1.95s', display: 'inline-block', transformStyle: 'preserve-3d' }}>&</span>
         
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
              <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop-gradient 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '2.05s', display: 'inline-block', transformStyle: 'preserve-3d' }}>V</span>
              <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop-gradient 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '2.1s', display: 'inline-block', transformStyle: 'preserve-3d' }}>I</span>
              <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop-gradient 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '2.15s', display: 'inline-block', transformStyle: 'preserve-3d' }}>S</span>
              <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop-gradient 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '2.2s', display: 'inline-block', transformStyle: 'preserve-3d' }}>T</span>
              <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop-gradient 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '2.25s', display: 'inline-block', transformStyle: 'preserve-3d' }}>A</span>
              <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop-gradient 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '2.3s', display: 'inline-block', transformStyle: 'preserve-3d' }}>M</span>
              <span style={{ animation: 'char-appear 0.05s ease-out forwards, char-pop-gradient 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards', animationDelay: '2.35s', display: 'inline-block', transformStyle: 'preserve-3d' }}>D</span>
            </span>
          </h2>
          <p style={{
            fontSize: 'clamp(1.1rem, 3vw, 1.5rem)',
            color: '#000000FF',
            fontWeight: '500',
            letterSpacing: '0.5px',
            animation: 'fade-in 0.8s ease-out 2.5s both, subtle-float 2s ease-in-out 2.5s infinite',
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
        /* ===== ANIMATIONS DISSOLUTION (DISSOLVE) ===== */
        
        @keyframes logo-dissolve-in {
          0% {
            transform: scale(0.3);
            opacity: 0;
            box-shadow: 0 0 0 rgba(34, 197, 94, 0);
            filter: blur(25px) saturate(0.3);
            clip-path: polygon(
              20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%
            );
          }
          30% {
            opacity: 0.5;
            filter: blur(15px) saturate(0.7);
            clip-path: polygon(
              10% 0%, 90% 0%, 100% 10%, 100% 90%, 90% 100%, 10% 100%, 0% 90%, 0% 10%
            );
          }
          60% {
            transform: scale(1.12);
            opacity: 1;
            filter: blur(0) saturate(1.2);
            clip-path: polygon(
              0% 0%, 100% 0%, 100% 0%, 100% 100%, 100% 100%, 0% 100%, 0% 100%, 0% 0%
            );
          }
          80% {
            transform: scale(0.96);
          }
          100% {
            transform: scale(1);
            opacity: 1;
            box-shadow: 0 20px 60px rgba(34, 197, 94, 0.4), 0 0 40px rgba(34, 197, 94, 0.3);
            filter: blur(0) saturate(1);
            clip-path: polygon(
              0% 0%, 100% 0%, 100% 0%, 100% 100%, 100% 100%, 0% 100%, 0% 100%, 0% 0%
            );
          }
        }

        @keyframes image-dissolve-zoom {
          0% {
            transform: scale(0.4);
            opacity: 0;
            filter: drop-shadow(0 0 0 rgba(34, 197, 94, 0)) blur(30px) saturate(0.2) contrast(0.5);
          }
          35% {
            opacity: 0.6;
            filter: drop-shadow(0 15px 45px rgba(34, 197, 94, 0.3)) blur(10px) saturate(0.8) contrast(0.9);
          }
          65% {
            transform: scale(1.1);
            opacity: 1;
            filter: drop-shadow(0 20px 60px rgba(34, 197, 94, 0.4)) blur(0) saturate(1.3) contrast(1.1);
          }
          85% {
            transform: scale(0.98);
          }
          100% {
            transform: scale(1);
            opacity: 1;
            filter: drop-shadow(0 20px 60px rgba(34, 197, 94, 0.4)) blur(0) saturate(1) contrast(1);
          }
        }

        @keyframes dissolve-fragment {
          0% {
            transform: translate(-50%, -50%) 
                       rotate(0deg) 
                       translateX(calc(cos(var(--angle)) * var(--distance))) 
                       translateY(calc(sin(var(--angle)) * var(--distance)))
                       scale(0);
            opacity: 0;
          }
          15% {
            opacity: 1;
          }
          50% {
            transform: translate(-50%, -50%) 
                       rotate(0deg) 
                       translateX(0px) 
                       translateY(0px)
                       scale(1);
            opacity: 1;
          }
          100% {
            transform: translate(-50%, -50%) 
                       rotate(0deg) 
                       translateX(0px) 
                       translateY(0px)
                       scale(1);
            opacity: 0;
          }
        }

        @keyframes logo-breathe {
          0%, 100% {
            transform: scale(1);
            box-shadow: 0 20px 60px rgba(34, 197, 94, 0.4), 0 0 40px rgba(34, 197, 94, 0.3);
          }
          50% {
            transform: scale(1.03);
            box-shadow: 0 25px 70px rgba(34, 197, 94, 0.5), 0 0 50px rgba(34, 197, 94, 0.4);
          }
        }

        @keyframes cross-appear {
          0% {
            transform: scale(0);
            opacity: 0;
            filter: blur(8px);
          }
          60% {
            transform: scale(1.25);
            filter: blur(0);
          }
          100% {
            transform: scale(1);
            opacity: 1;
            filter: blur(0);
          }
        }

        @keyframes fragment-explode {
          0% {
            transform: translate(-50%, -50%) translateX(0px) translateY(0px) scale(1);
            opacity: 1;
          }
          100% {
            transform: translate(-50%, -50%) 
                       translateX(calc(cos(var(--angle)) * calc(var(--distance) * 2)))
                       translateY(calc(sin(var(--angle)) * calc(var(--distance) * 2)))
                       rotate(calc(var(--angle) * 2))
                       scale(0);
            opacity: 0;
          }
        }

        /* ===== ANIMATIONS EXISTANTES ===== */
        
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