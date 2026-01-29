// src/components/home/TestimonialsSection.tsx
import { useState, useEffect, useRef } from 'react'
import { Star, ChevronLeft, ChevronRight, Quote } from 'lucide-react'

const TestimonialsSection = () => {
  const [currentIndex, setCurrentIndex] = useState(0)
  const [isAutoplay, setIsAutoplay] = useState(true)
  const [isVisible, setIsVisible] = useState(false)
  const [hoveredCard, setHoveredCard] = useState<number | null>(null)
  const sectionRef = useRef<HTMLDivElement>(null)

  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        setIsVisible(entry.isIntersecting)
      },
      {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
      }
    )

    if (sectionRef.current) {
      observer.observe(sectionRef.current)
    }

    return () => {
      if (sectionRef.current) {
        observer.unobserve(sectionRef.current)
      }
    }
  }, [])

  const testimonials = [
    {
      id: 1,
      name: 'Jean Kouadio',
      role: 'Patient',
      text: 'Excellent service ! L\'équipe médicale de CMO VISTAMD est très professionnelle et attentionnée. Ils ont pris soin de moi avec beaucoup de compassion.',
      rating: 5,
      avatar: '👨‍⚕️',
      image: '/assets/testimonials/01.png'
    },
    {
      id: 2,
      name: 'Marie Dubois',
      role: 'Directrice Clinique',
      text: 'Partenaire fiable et compétent. CMO VISTAMD nous aide régulièrement avec le transport médicalisé et la maintenance d\'équipements. Très recommandé !',
      rating: 5,
      avatar: '👩‍⚕️',
      image: '/assets/testimonials/02.png'
    },
    {
      id: 3,
      name: 'Yannick Sow',
      role: 'Patient',
      text: 'Service d\'urgence impressionnant ! Ils sont arrivés en 15 minutes. L\'équipe était calme, efficace et rassurante. Merci CMO VISTAMD !',
      rating: 5,
      avatar: '👨‍💼',
      image: '/assets/testimonials/03.png'
    },
    {
      id: 4,
      name: 'Fatou Ba',
      role: 'Responsable Formation',
      text: 'Les formations proposées par CMO VISTAMD sont de très haute qualité. Notre personnel a beaucoup appris sur la gestion des équipements médicaux.',
      rating: 5,
      avatar: '👩‍🏫',
      image: '/assets/testimonials/04.png'
    },
    {
      id: 5,
      name: 'Ahmed Hassan',
      role: 'Patient',
      text: 'Transport médicalisé sûr et confortable. Les ambulanciers étaient expérimentés et bienveillants. Un grand merci à toute l\'équipe !',
      rating: 5,
      avatar: '👨‍🦱',
      image: '/assets/testimonials/05.png'
    },
    {
      id: 6,
      name: 'Carla Martinez',
      role: 'Coordinatrice Médicale',
      text: 'CMO VISTAMD montre un vrai engagement envers l\'excellence médicale. Leur assistance nous a énormément aidés dans nos opérations quotidiennes.',
      rating: 5,
      avatar: '👩‍💻',
      image: '/assets/testimonials/06.png'
    }
  ]

  // Auto-slide
  useEffect(() => {
    if (!isAutoplay) return
    const timer = setInterval(() => {
      setCurrentIndex((prev) => (prev + 1) % testimonials.length)
    }, 5000)
    return () => clearInterval(timer)
  }, [isAutoplay, testimonials.length])

  const nextSlide = () => {
    setCurrentIndex((prev) => (prev + 1) % testimonials.length)
    setIsAutoplay(false)
  }

  const prevSlide = () => {
    setCurrentIndex((prev) => (prev - 1 + testimonials.length) % testimonials.length)
    setIsAutoplay(false)
  }

  // Affiche 3 slides visibles
  const visibleSlides = [
    testimonials[currentIndex],
    testimonials[(currentIndex + 1) % testimonials.length],
    testimonials[(currentIndex + 2) % testimonials.length]
  ]

  return (
    <div 
      ref={sectionRef}
      id='testimonials' 
      className="rts-testimonials-area rts-section-gap" 
      style={{
        background: 'linear-gradient(180deg, #f1f5f9 0%, #e2e8f0 50%, #f8fafc 100%)',
        padding: '100px 0',
        position: 'relative',
        overflow: 'hidden'
      }}
    >
      {/* Éléments de décoration animés avec fond coloré */}
      <div style={{
        position: 'absolute',
        top: '10%',
        right: '-8%',
        width: '450px',
        height: '450px',
        background: 'radial-gradient(circle, rgba(34, 197, 94, 0.15) 0%, rgba(34, 197, 94, 0.05) 50%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(50px)',
        pointerEvents: 'none',
        animation: 'float 10s ease-in-out infinite'
      }}></div>
      <div style={{
        position: 'absolute',
        bottom: '10%',
        left: '-8%',
        width: '450px',
        height: '450px',
        background: 'radial-gradient(circle, rgba(16, 117, 185, 0.15) 0%, rgba(16, 117, 185, 0.05) 50%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(50px)',
        pointerEvents: 'none',
        animation: 'float 12s ease-in-out infinite 2s'
      }}></div>

      {/* SVG Medical Icons - Top Right - PLUS VISIBLE */}
      <svg 
        style={{
          position: 'absolute',
          top: '8%',
          right: '5%',
          width: '180px',
          height: '180px',
          opacity: 0.5,
          animation: 'rotate 20s linear infinite',
          filter: 'drop-shadow(0 6px 12px rgba(34, 197, 94, 0.2))'
        }}
        viewBox="0 0 100 100"
      >
        <circle cx="50" cy="50" r="45" fill="rgba(34, 197, 94, 0.1)" stroke="#22c55e" strokeWidth="4" strokeDasharray="5,5"/>
        <path d="M50 20 L50 80 M20 50 L80 50" stroke="#22c55e" strokeWidth="12" strokeLinecap="round"/>
        <circle cx="50" cy="50" r="35" fill="none" stroke="#10b981" strokeWidth="3" opacity="0.7"/>
        <circle cx="50" cy="50" r="25" fill="rgba(16, 185, 129, 0.1)"/>
      </svg>

      {/* SVG Heart with Pulse - Top Left - PLUS VISIBLE */}
      <svg 
        style={{
          position: 'absolute',
          top: '12%',
          left: '6%',
          width: '140px',
          height: '140px',
          opacity: 0.45,
          animation: 'heartbeat 2s ease-in-out infinite',
          filter: 'drop-shadow(0 6px 14px rgba(34, 197, 94, 0.25))'
        }}
        viewBox="0 0 100 100"
      >
        <path 
          d="M50,85 C50,85 15,60 15,40 C15,25 25,20 35,25 C40,28 45,35 50,40 C55,35 60,28 65,25 C75,20 85,25 85,40 C85,60 50,85 50,85 Z" 
          fill="#22c55e"
        />
        <path 
          d="M20,45 L30,45 L35,35 L40,55 L45,45 L55,45" 
          fill="none" 
          stroke="white" 
          strokeWidth="3.5"
          strokeLinecap="round"
          strokeLinejoin="round"
        />
      </svg>

      {/* SVG Stethoscope - Bottom Right - PLUS VISIBLE */}
      <svg 
        style={{
          position: 'absolute',
          bottom: '10%',
          right: '8%',
          width: '130px',
          height: '130px',
          opacity: 0.45,
          animation: 'float 8s ease-in-out infinite 1s',
          filter: 'drop-shadow(0 6px 14px rgba(16, 117, 185, 0.25))'
        }}
        viewBox="0 0 100 100"
      >
        <circle cx="50" cy="25" r="9" fill="rgba(16, 117, 185, 0.3)" stroke="#1075B9" strokeWidth="5"/>
        <path d="M50,33 L50,60 Q50,75 65,75" fill="none" stroke="#1075B9" strokeWidth="5" strokeLinecap="round"/>
        <circle cx="65" cy="75" r="11" fill="#1075B9"/>
        <circle cx="65" cy="75" r="7" fill="#0ea5e9"/>
        <circle cx="65" cy="75" r="3" fill="white"/>
        <path d="M42,33 Q35,40 35,50" fill="none" stroke="#1075B9" strokeWidth="4" strokeLinecap="round"/>
        <path d="M58,33 Q65,40 65,50" fill="none" stroke="#1075B9" strokeWidth="4" strokeLinecap="round"/>
      </svg>

      {/* SVG Ambulance - Bottom Left - PLUS VISIBLE */}
      <svg 
        style={{
          position: 'absolute',
          bottom: '15%',
          left: '5%',
          width: '150px',
          height: '150px',
          opacity: 0.4,
          animation: 'slideHorizontal 15s ease-in-out infinite',
          filter: 'drop-shadow(0 6px 14px rgba(34, 197, 94, 0.3))'
        }}
        viewBox="0 0 100 100"
      >
        {/* Carrosserie */}
        <rect x="20" y="35" width="50" height="30" rx="4" fill="#22c55e"/>
        <rect x="55" y="40" width="15" height="20" rx="3" fill="#10b981"/>
        
        {/* Roues */}
        <circle cx="30" cy="65" r="8" fill="#334155"/>
        <circle cx="30" cy="65" r="5" fill="#64748b"/>
        <circle cx="30" cy="65" r="2" fill="#94a3b8"/>
        <circle cx="60" cy="65" r="8" fill="#334155"/>
        <circle cx="60" cy="65" r="5" fill="#64748b"/>
        <circle cx="60" cy="65" r="2" fill="#94a3b8"/>
        
        {/* Croix médicale */}
        <path d="M35,45 L35,55 M30,50 L40,50" stroke="white" strokeWidth="4" strokeLinecap="round"/>
        
        {/* Fenêtres */}
        <rect x="23" y="42" width="12" height="10" fill="#e0f2fe" rx="1"/>
        <rect x="43" y="42" width="10" height="10" fill="#e0f2fe" rx="1"/>
        <rect x="58" y="45" width="10" height="8" fill="#e0f2fe" rx="1"/>
        
        {/* Gyrophare */}
        <rect x="40" y="32" width="10" height="3" fill="#ef4444" rx="1"/>
        <ellipse cx="45" cy="30" rx="4" ry="2" fill="#fca5a5"/>
        
        {/* Phares */}
        <circle cx="68" cy="50" r="2" fill="#fbbf24"/>
        <circle cx="68" cy="56" r="2" fill="#fbbf24"/>
      </svg>

      {/* Decorative Medical Shapes - PLUS VISIBLE */}
      <div style={{
        position: 'absolute',
        top: '30%',
        right: '2%',
        width: '100px',
        height: '100px',
        opacity: 0.35,
        animation: 'rotate 25s linear infinite reverse',
        filter: 'drop-shadow(0 4px 8px rgba(34, 197, 94, 0.25))'
      }}>
        <div style={{
          width: '100%',
          height: '100%',
          background: 'linear-gradient(45deg, #22c55e 25%, #f1f5f9 25%, #f1f5f9 75%, #22c55e 75%, #22c55e), linear-gradient(45deg, #22c55e 25%, #f1f5f9 25%, #f1f5f9 75%, #22c55e 75%, #22c55e)',
          backgroundSize: '25px 25px',
          backgroundPosition: '0 0, 12.5px 12.5px',
          borderRadius: '20%'
        }}></div>
      </div>

      {/* DNA Helix Decoration - PLUS VISIBLE */}
      <svg 
        style={{
          position: 'absolute',
          top: '45%',
          left: '1%',
          width: '120px',
          height: '200px',
          opacity: 0.4,
          animation: 'float 10s ease-in-out infinite 3s',
          filter: 'drop-shadow(0 4px 10px rgba(16, 117, 185, 0.25))'
        }}
        viewBox="0 0 50 100"
      >
        <path d="M10,10 Q25,25 10,40 Q25,55 10,70 Q25,85 10,100" fill="none" stroke="#1075B9" strokeWidth="4" strokeLinecap="round"/>
        <path d="M40,10 Q25,25 40,40 Q25,55 40,70 Q25,85 40,100" fill="none" stroke="#22c55e" strokeWidth="4" strokeLinecap="round"/>
        {[...Array(7)].map((_, i) => (
          <line key={i} x1="10" y1={12 + i * 14} x2="40" y2={12 + i * 14} stroke="#64748b" strokeWidth="2.5" opacity="0.7"/>
        ))}
        {[...Array(7)].map((_, i) => (
          <circle key={`dot-left-${i}`} cx="10" cy={12 + i * 14} r="3" fill="#1075B9"/>
        ))}
        {[...Array(7)].map((_, i) => (
          <circle key={`dot-right-${i}`} cx="40" cy={12 + i * 14} r="3" fill="#22c55e"/>
        ))}
      </svg>

      {/* Pill/Capsule Icon - Top Center - NOUVEAU */}
      <svg 
        style={{
          position: 'absolute',
          top: '5%',
          left: '50%',
          transform: 'translateX(-50%)',
          width: '100px',
          height: '100px',
          opacity: 0.35,
          animation: 'float 9s ease-in-out infinite 2s',
          filter: 'drop-shadow(0 4px 8px rgba(34, 197, 94, 0.2))'
        }}
        viewBox="0 0 100 100"
      >
        <ellipse cx="50" cy="50" rx="15" ry="35" fill="#22c55e" transform="rotate(45 50 50)"/>
        <ellipse cx="50" cy="50" rx="15" ry="35" fill="#0ea5e9" transform="rotate(45 50 50)" clipPath="polygon(0 0, 100% 0, 100% 50%, 0 50%)"/>
        <line x1="30" y1="30" x2="70" y2="70" stroke="white" strokeWidth="2" opacity="0.5"/>
      </svg>

      {/* Medical Kit Icon - Bottom Center Right - NOUVEAU */}
      <svg 
        style={{
          position: 'absolute',
          bottom: '20%',
          right: '35%',
          width: '90px',
          height: '90px',
          opacity: 0.3,
          animation: 'float 11s ease-in-out infinite 4s',
          filter: 'drop-shadow(0 4px 8px rgba(239, 68, 68, 0.2))'
        }}
        viewBox="0 0 100 100"
      >
        <rect x="25" y="40" width="50" height="40" rx="4" fill="#ef4444"/>
        <rect x="25" y="35" width="50" height="8" fill="#dc2626" rx="2"/>
        <rect x="40" y="30" width="20" height="10" fill="#b91c1c" rx="2"/>
        <path d="M50 52 L50 68 M42 60 L58 60" stroke="white" strokeWidth="4" strokeLinecap="round"/>
        <circle cx="30" cy="39" r="2" fill="#fca5a5"/>
        <circle cx="70" cy="39" r="2" fill="#fca5a5"/>
      </svg>

      {/* Particules décoratives colorées */}
      <div style={{
        position: 'absolute',
        top: 0,
        left: 0,
        width: '100%',
        height: '100%',
        pointerEvents: 'none',
        zIndex: 1
      }}>
        {[...Array(12)].map((_, i) => (
          <div
            key={i}
            style={{
              position: 'absolute',
              width: i % 3 === 0 ? '8px' : '5px',
              height: i % 3 === 0 ? '8px' : '5px',
              background: i % 3 === 0 ? '#22c55e' : i % 3 === 1 ? '#1075B9' : '#ef4444',
              borderRadius: '50%',
              top: `${10 + Math.random() * 80}%`,
              left: `${10 + Math.random() * 80}%`,
              opacity: 0.5,
              boxShadow: `0 0 10px ${i % 3 === 0 ? '#22c55e' : i % 3 === 1 ? '#1075B9' : '#ef4444'}`,
              animation: `particleFloat ${8 + Math.random() * 4}s ease-in-out infinite ${Math.random() * 5}s`
            }}
          />
        ))}
      </div>

      <div className="container" style={{ position: 'relative', zIndex: 2 }}>
        {/* Header */}
        <div style={{
          textAlign: 'center',
          marginBottom: '4rem',
          opacity: isVisible ? 1 : 0,
          transform: isVisible ? 'translateY(0)' : 'translateY(-30px)',
          transition: 'opacity 0.6s ease-out 0.2s, transform 0.6s ease-out 0.2s'
        }}>
          {/* Badge avec icône */}
          <div style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '0.5rem',
            background: 'rgba(34, 197, 94, 0.15)',
            padding: '0.6rem 1.2rem',
            borderRadius: '2rem',
            marginBottom: '1rem',
            animation: isVisible ? 'pulse 2s ease-in-out infinite' : 'none',
            boxShadow: '0 4px 12px rgba(34, 197, 94, 0.2)'
          }}>
            <Quote size={20} style={{ color: '#22c55e' }} />
            <span style={{
              color: '#22c55e',
              fontWeight: '700',
              fontSize: '0.9rem',
              textTransform: 'uppercase',
              letterSpacing: '0.05em'
            }}>
              Témoignages
            </span>
          </div>

          <h2 style={{
            fontSize: '2.5rem',
            fontWeight: 'bold',
            color: '#0f172a',
            lineHeight: '1.3',
            marginBottom: '1rem',
            opacity: isVisible ? 1 : 0,
            transform: isVisible ? 'scale(1)' : 'scale(0.9)',
            transition: 'opacity 0.6s ease-out 0.3s, transform 0.6s ease-out 0.3s'
          }}>
            1200+ Avis de<br />
            <span style={{
              background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
              WebkitBackgroundClip: 'text',
              WebkitTextFillColor: 'transparent',
              backgroundClip: 'text'
            }}>
              Nos Patients
            </span>
          </h2>
        </div>

        {/* Carousel Container */}
        <div style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))',
          gap: '2rem',
          marginBottom: '3rem',
          opacity: isVisible ? 1 : 0,
          transform: isVisible ? 'translateY(0)' : 'translateY(50px)',
          transition: 'opacity 0.6s ease-out 0.4s, transform 0.6s ease-out 0.4s'
        }}>
          {visibleSlides.map((testimonial, index) => (
            <div
              key={testimonial.id}
              style={{
                padding: '2.5rem 2rem',
                background: '#ffffff',
                border: hoveredCard === index ? '2px solid #22c55e' : '2px solid rgba(34, 197, 94, 0.15)',
                borderRadius: '1rem',
                boxShadow: hoveredCard === index 
                  ? '0 20px 60px rgba(34, 197, 94, 0.2)' 
                  : '0 15px 50px rgba(0, 0, 0, 0.08)',
                transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)',
                position: 'relative',
                overflow: 'hidden',
                opacity: isVisible ? 1 : 0,
                transform: isVisible ? 'scale(1)' : 'scale(0.9)',
                transitionDelay: `${0.5 + index * 0.1}s`,
                cursor: 'pointer'
              }}
              onMouseEnter={() => setHoveredCard(index)}
              onMouseLeave={() => setHoveredCard(null)}
            >
              {/* Effet de vague au survol */}
              <div style={{
                position: 'absolute',
                top: '50%',
                left: '50%',
                width: hoveredCard === index ? '250%' : '0%',
                height: hoveredCard === index ? '250%' : '0%',
                background: 'radial-gradient(circle, rgba(34, 197, 94, 0.1) 0%, transparent 70%)',
                transform: 'translate(-50%, -50%)',
                transition: 'all 0.6s ease',
                borderRadius: '50%',
                pointerEvents: 'none'
              }}></div>

              {/* Quote Icon avec animation */}
              <div style={{
                fontSize: '4rem',
                marginBottom: '1rem',
                opacity: 0.2,
                color: '#22c55e',
                lineHeight: 0,
                position: 'relative',
                zIndex: 1,
                transition: 'all 0.3s ease',
                transform: hoveredCard === index ? 'scale(1.2) rotate(-10deg)' : 'scale(1)'
              }}>
                "
              </div>

              {/* Stars avec animation */}
              <div style={{
                display: 'flex',
                gap: '0.3rem',
                marginBottom: '1.5rem',
                position: 'relative',
                zIndex: 1
              }}>
                {[...Array(testimonial.rating)].map((_, i) => (
                  <Star
                    key={i}
                    size={18}
                    style={{
                      fill: '#fbbf24',
                      color: '#fbbf24',
                      animation: hoveredCard === index ? `starBounce 0.6s ease ${i * 0.1}s` : 'none'
                    }}
                  />
                ))}
              </div>

              {/* Testimonial Text */}
              <p style={{
                fontSize: '1rem',
                color: '#64748b',
                lineHeight: '1.8',
                marginBottom: '2rem',
                minHeight: '80px',
                position: 'relative',
                zIndex: 1
              }}>
                {testimonial.text}
              </p>

              {/* Author Info */}
              <div style={{
                display: 'flex',
                alignItems: 'center',
                gap: '1rem',
                paddingTop: '1.5rem',
                borderTop: '1px solid rgba(34, 197, 94, 0.15)',
                position: 'relative',
                zIndex: 1
              }}>
                {/* Avatar avec animation */}
                <div style={{
                  width: '50px',
                  height: '50px',
                  background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
                  borderRadius: '50%',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  fontSize: '1.5rem',
                  flexShrink: 0,
                  transition: 'all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)',
                  transform: hoveredCard === index ? 'scale(1.15) rotate(10deg)' : 'scale(1)',
                  boxShadow: hoveredCard === index ? '0 8px 20px rgba(34, 197, 94, 0.4)' : 'none'
                }}>
                  {testimonial.avatar}
                </div>

                {/* Name & Role */}
                <div>
                  <h5 style={{
                    fontSize: '1rem',
                    fontWeight: 'bold',
                    color: hoveredCard === index ? '#22c55e' : '#0f172a',
                    margin: '0 0 0.3rem 0',
                    transition: 'color 0.3s ease'
                  }}>
                    {testimonial.name}
                  </h5>
                  <p style={{
                    fontSize: '0.85rem',
                    color: '#64748b',
                    margin: 0
                  }}>
                    {testimonial.role}
                  </p>
                </div>
              </div>

              {/* Badge "Vérifié" au survol */}
              <div style={{
                position: 'absolute',
                top: '15px',
                right: '15px',
                background: 'linear-gradient(135deg, #22c55e, #10b981)',
                color: 'white',
                padding: '0.4rem 0.8rem',
                borderRadius: '2rem',
                fontSize: '0.75rem',
                fontWeight: 'bold',
                display: 'flex',
                alignItems: 'center',
                gap: '0.3rem',
                opacity: hoveredCard === index ? 1 : 0,
                transform: hoveredCard === index ? 'translateY(0) scale(1)' : 'translateY(-10px) scale(0.8)',
                transition: 'all 0.3s ease',
                boxShadow: '0 4px 12px rgba(34, 197, 94, 0.4)'
              }}>
                <span>✓</span>
                <span>Vérifié</span>
              </div>

              {/* Medical Icon SVG in card - PLUS VISIBLE */}
              <svg 
                style={{
                  position: 'absolute',
                  bottom: 15,
                  right: 15,
                  width: '50px',
                  height: '50px',
                  opacity: hoveredCard === index ? 0.4 : 0.2,
                  transition: 'all 0.4s ease',
                  transform: hoveredCard === index ? 'scale(1.2) rotate(15deg)' : 'scale(1)',
                  filter: 'drop-shadow(0 2px 4px rgba(34, 197, 94, 0.2))'
                }}
                viewBox="0 0 50 50"
              >
                {index % 3 === 0 && (
                  // Heart icon
                  <>
                    <path d="M25,45 C25,45 8,30 8,18 C8,10 15,8 20,12 C22,14 23,18 25,20 C27,18 28,14 30,12 C35,8 42,10 42,18 C42,30 25,45 25,45 Z" 
                      fill="#22c55e"
                    />
                    <circle cx="25" cy="22" r="2" fill="white" opacity="0.6"/>
                  </>
                )}
                {index % 3 === 1 && (
                  // Medical cross
                  <>
                    <circle cx="25" cy="25" r="20" fill="rgba(34, 197, 94, 0.1)" stroke="#22c55e" strokeWidth="3"/>
                    <path d="M25 12 L25 38 M12 25 L38 25" stroke="#22c55e" strokeWidth="5" strokeLinecap="round"/>
                  </>
                )}
                {index % 3 === 2 && (
                  // Shield with cross
                  <>
                    <path d="M25,5 L40,10 L40,25 Q40,40 25,45 Q10,40 10,25 L10,10 Z" 
                      fill="rgba(34, 197, 94, 0.1)" 
                      stroke="#22c55e" 
                      strokeWidth="3"
                    />
                    <path d="M25 15 L25 35 M15 25 L35 25" stroke="#22c55e" strokeWidth="4" strokeLinecap="round"/>
                  </>
                )}
              </svg>

              {/* Shape decoration (top right) */}
              <div style={{
                position: 'absolute',
                top: -20,
                right: -20,
                width: '80px',
                height: '80px',
                background: 'linear-gradient(135deg, rgba(34, 197, 94, 0.08) 0%, transparent 100%)',
                borderRadius: '50%',
                pointerEvents: 'none',
                transition: 'all 0.3s ease',
                transform: hoveredCard === index ? 'scale(1.5)' : 'scale(1)'
              }}></div>
            </div>
          ))}
        </div>

        {/* Navigation - Bottom */}
        <div style={{
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          gap: '1.5rem',
          opacity: isVisible ? 1 : 0,
          transform: isVisible ? 'translateY(0)' : 'translateY(30px)',
          transition: 'opacity 0.6s ease-out 0.8s, transform 0.6s ease-out 0.8s'
        }}>
          {/* Previous Button */}
          <button
            onClick={prevSlide}
            style={{
              width: '48px',
              height: '48px',
              background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
              border: 'none',
              borderRadius: '50%',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              color: 'white',
              cursor: 'pointer',
              transition: 'all 0.3s ease',
              boxShadow: '0 10px 25px rgba(34, 197, 94, 0.3)'
            }}
            onMouseEnter={(e) => {
              e.currentTarget.style.transform = 'scale(1.15) rotate(-10deg)'
              e.currentTarget.style.boxShadow = '0 15px 35px rgba(34, 197, 94, 0.5)'
            }}
            onMouseLeave={(e) => {
              e.currentTarget.style.transform = 'scale(1) rotate(0deg)'
              e.currentTarget.style.boxShadow = '0 10px 25px rgba(34, 197, 94, 0.3)'
            }}
          >
            <ChevronLeft size={22} />
          </button>

          {/* Pagination Dots */}
          <div style={{
            display: 'flex',
            gap: '0.6rem',
            alignItems: 'center'
          }}>
            {testimonials.map((_, index) => (
              <button
                key={index}
                onClick={() => setCurrentIndex(index)}
                style={{
                  width: currentIndex === index ? '28px' : '10px',
                  height: '10px',
                  background: currentIndex === index 
                    ? 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)' 
                    : 'rgba(34, 197, 94, 0.3)',
                  border: 'none',
                  borderRadius: '50px',
                  cursor: 'pointer',
                  transition: 'all 0.3s ease',
                  boxShadow: currentIndex === index ? '0 4px 12px rgba(34, 197, 94, 0.4)' : 'none'
                }}
                onMouseEnter={(e) => {
                  e.currentTarget.style.transform = 'scale(1.2)'
                }}
                onMouseLeave={(e) => {
                  e.currentTarget.style.transform = 'scale(1)'
                }}
              />
            ))}
          </div>

          {/* Next Button */}
          <button
            onClick={nextSlide}
            style={{
              width: '48px',
              height: '48px',
              background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
              border: 'none',
              borderRadius: '50%',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              color: 'white',
              cursor: 'pointer',
              transition: 'all 0.3s ease',
              boxShadow: '0 10px 25px rgba(34, 197, 94, 0.3)'
            }}
            onMouseEnter={(e) => {
              e.currentTarget.style.transform = 'scale(1.15) rotate(10deg)'
              e.currentTarget.style.boxShadow = '0 15px 35px rgba(34, 197, 94, 0.5)'
            }}
            onMouseLeave={(e) => {
              e.currentTarget.style.transform = 'scale(1) rotate(0deg)'
              e.currentTarget.style.boxShadow = '0 10px 25px rgba(34, 197, 94, 0.3)'
            }}
          >
            <ChevronRight size={22} />
          </button>
        </div>
      </div>

      <style>{`
        @keyframes slideInDown {
          from {
            opacity: 0;
            transform: translateY(-30px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        @keyframes slideInUp {
          from {
            opacity: 0;
            transform: translateY(30px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        @keyframes float {
          0%, 100% {
            transform: translateY(0px);
          }
          50% {
            transform: translateY(30px);
          }
        }

        @keyframes pulse {
          0%, 100% {
            opacity: 1;
            transform: scale(1);
          }
          50% {
            opacity: 0.8;
            transform: scale(1.05);
          }
        }

        @keyframes starBounce {
          0%, 100% {
            transform: translateY(0) scale(1);
          }
          50% {
            transform: translateY(-5px) scale(1.2);
          }
        }

        @keyframes particleFloat {
          0%, 100% {
            transform: translateY(0) translateX(0);
            opacity: 0.5;
          }
          25% {
            opacity: 0.8;
          }
          50% {
            transform: translateY(-20px) translateX(10px);
            opacity: 0.5;
          }
          75% {
            opacity: 0.8;
          }
        }

        @keyframes rotate {
          from {
            transform: rotate(0deg);
          }
          to {
            transform: rotate(360deg);
          }
        }

        @keyframes heartbeat {
          0%, 100% {
            transform: scale(1);
          }
          10%, 30% {
            transform: scale(1.1);
          }
          20%, 40% {
            transform: scale(1);
          }
        }

        @keyframes slideHorizontal {
          0%, 100% {
            transform: translateX(0);
          }
          50% {
            transform: translateX(20px);
          }
        }
      `}</style>
    </div>
  )
}

export default TestimonialsSection