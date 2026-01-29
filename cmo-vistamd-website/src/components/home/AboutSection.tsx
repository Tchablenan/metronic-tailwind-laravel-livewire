// src/components/home/AboutSection.tsx
import { useState, useEffect, useRef } from 'react'
import { Heart, Award, Users, Shield } from 'lucide-react'

const AboutSection = () => {
  const [hoveredStat, setHoveredStat] = useState<number | null>(null)
  const [hoveredFeature, setHoveredFeature] = useState<number | null>(null)
  const [isVisible, setIsVisible] = useState(false)
  const sectionRef = useRef<HTMLDivElement>(null)

  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        // Animation se déclenche à chaque fois que la section entre dans le viewport
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

  const stats = [
    { icon: Heart, label: 'Patients Satisfaits', value: '2500+', color: '#22c55e' },
    { icon: Award, label: 'Années Expérience', value: '15+', color: '#1075B9FF' },
    { icon: Users, label: 'Professionnels Médicaux', value: '50+', color: '#22c55e' },
    { icon: Shield, label: 'Services 24/7', value: '365j', color: '#1075B9FF' }
  ]

  const features = [
    {
      title: 'Soins Centrés sur le Patient',
      description: 'Nous plaçons le bien-être du patient au cœur de chaque décision médicale',
      icon: '🏥'
    },
    {
      title: 'Support d\'Urgence',
      description: 'Équipe d\'urgence disponible 24/7 pour les situations critiques',
      icon: '🚑'
    },
    {
      title: 'Expertise Médicale',
      description: 'Professionnels qualifiés avec expertise reconnue en Côte d\'Ivoire',
      icon: '⚕️'
    },
    {
      title: 'Transport Médicalisé',
      description: 'Ambulances équipées et personnel formé pour le transport sécurisé',
      icon: '🚨'
    }
  ]

  return (
    <div 
      ref={sectionRef}
      id='about' 
      data-section='about'
      className="rts-about-area rts-section-gap" 
      style={{
        background: '#ffffff',
        padding: '100px 0',
        position: 'relative',
        overflow: 'hidden'
      }}
    >
      {/* Éléments de décoration animés */}
      <div style={{
        position: 'absolute',
        top: '10%',
        left: '-5%',
        width: '400px',
        height: '400px',
        background: 'radial-gradient(circle, rgba(34, 197, 94, 0.08) 0%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(60px)',
        pointerEvents: 'none',
        animation: 'float 10s ease-in-out infinite'
      }}></div>
      <div style={{
        position: 'absolute',
        bottom: '10%',
        right: '-5%',
        width: '400px',
        height: '400px',
        background: 'radial-gradient(circle, rgba(16, 117, 185, 0.08) 0%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(60px)',
        pointerEvents: 'none',
        animation: 'float 12s ease-in-out infinite 3s'
      }}></div>

      {/* Particules décoratives */}
      <div style={{
        position: 'absolute',
        top: 0,
        left: 0,
        width: '100%',
        height: '100%',
        pointerEvents: 'none',
        zIndex: 1
      }}>
        {[...Array(6)].map((_, i) => (
          <div
            key={i}
            style={{
              position: 'absolute',
              width: '3px',
              height: '3px',
              background: i % 2 === 0 ? '#22c55e' : '#1075B9',
              borderRadius: '50%',
              top: `${20 + Math.random() * 60}%`,
              left: `${10 + Math.random() * 80}%`,
              opacity: 0.4,
              animation: `particleFloat ${8 + Math.random() * 4}s ease-in-out infinite ${Math.random() * 5}s`
            }}
          />
        ))}
      </div>

      <div className="container" style={{ position: 'relative', zIndex: 2 }}>
        <div className="row" style={{ 
          display: 'grid', 
          gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', 
          gap: '4rem', 
          alignItems: 'center' 
        }}>
          
          {/* COLONNE GAUCHE - IMAGES */}
          <div style={{
            position: 'relative',
            minHeight: '600px'
          }}>
            {/* Image principale avec effet de bordure animée */}
            <div style={{
              position: 'relative',
              height: '500px',
              borderRadius: '1.5rem',
              overflow: 'hidden',
              boxShadow: '0 30px 80px rgba(34, 197, 94, 0.15)',
              opacity: isVisible ? 1 : 0,
              transform: isVisible ? 'translateX(0)' : 'translateX(-100px)',
              transition: 'opacity 0.8s ease-out, transform 0.8s ease-out',
              animation: isVisible ? 'floatImage 6s ease-in-out infinite 1s' : 'none'
            }}>
              {/* Bordure animée */}
              <div style={{
                position: 'absolute',
                top: '-2px',
                left: '-2px',
                right: '-2px',
                bottom: '-2px',
                background: 'linear-gradient(135deg, #22c55e, #1075B9, #22c55e)',
                borderRadius: '1.5rem',
                zIndex: -1,
                animation: 'rotateBorder 4s linear infinite',
                backgroundSize: '200% 200%'
              }}></div>

              <img 
                src="assets/about.jpg" 
                alt="CMO VISTAMD"
                style={{
                  width: '100%',
                  height: '100%',
                  objectFit: 'cover',
                  objectPosition: 'center',
                  borderRadius: '1.5rem',
                  transition: 'transform 0.5s ease'
                }}
                onMouseEnter={(e) => {
                  e.currentTarget.style.transform = 'scale(1.05)'
                }}
                onMouseLeave={(e) => {
                  e.currentTarget.style.transform = 'scale(1)'
                }}
              />

              {/* Badge animé sur l'image */}
              <div style={{
                position: 'absolute',
                top: '20px',
                left: '20px',
                background: 'rgba(34, 197, 94, 0.95)',
                backdropFilter: 'blur(10px)',
                padding: '0.75rem 1.25rem',
                borderRadius: '2rem',
                display: 'flex',
                alignItems: 'center',
                gap: '0.5rem',
                opacity: isVisible ? 1 : 0,
                transform: isVisible ? 'translateY(0) scale(1)' : 'translateY(-30px) scale(0.8)',
                transition: 'opacity 0.8s ease-out 0.5s, transform 0.8s ease-out 0.5s',
                animation: isVisible ? 'pulse 2s ease-in-out infinite 2s' : 'none',
                boxShadow: '0 10px 30px rgba(34, 197, 94, 0.3)'
              }}>
                <span style={{
                  width: '8px',
                  height: '8px',
                  background: 'white',
                  borderRadius: '50%',
                  animation: 'blink 2s ease-in-out infinite'
                }}></span>
                <span style={{
                  color: 'white',
                  fontWeight: 'bold',
                  fontSize: '0.95rem'
                }}>Certifié Excellence</span>
              </div>
            </div>

            {/* Carte de review - Positionnée en bas à droite avec animations */}
            <div style={{
              position: 'absolute',
              bottom: '20px',
              right: '0',
              background: 'rgba(255, 255, 255, 0.98)',
              backdropFilter: 'blur(10px)',
              borderRadius: '1rem',
              padding: '1.75rem',
              boxShadow: '0 25px 70px rgba(0, 0, 0, 0.12)',
              opacity: isVisible ? 1 : 0,
              transform: isVisible ? 'translateY(0)' : 'translateY(50px)',
              transition: 'opacity 0.8s ease-out 0.3s, transform 0.8s ease-out 0.3s',
              animation: isVisible ? 'floatSlow 5s ease-in-out infinite 2s' : 'none',
              zIndex: 10,
              width: '320px',
              border: '1px solid rgba(34, 197, 94, 0.1)'
            }}
            onMouseEnter={(e) => {
              e.currentTarget.style.transform = 'translateY(-10px) scale(1.02)'
              e.currentTarget.style.boxShadow = '0 30px 80px rgba(34, 197, 94, 0.2)'
            }}
            onMouseLeave={(e) => {
              e.currentTarget.style.transform = 'translateY(0) scale(1)'
              e.currentTarget.style.boxShadow = '0 25px 70px rgba(0, 0, 0, 0.12)'
            }}>
              <div style={{
                marginBottom: '1rem',
                background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
                WebkitBackgroundClip: 'text',
                WebkitTextFillColor: 'transparent',
                backgroundClip: 'text',
                fontWeight: 'bold',
                fontSize: '1.3rem',
                animation: 'shimmerText 3s ease-in-out infinite'
              }}>
                Excellence Reconnue
              </div>
              <div style={{
                display: 'flex',
                gap: '0.5rem',
                marginBottom: '0.75rem'
              }}>
                {[...Array(5)].map((_, i) => (
                  <span key={i} style={{
                    color: '#fbbf24',
                    fontSize: '1.3rem',
                    animation: `starBounce 1s ease-in-out infinite ${i * 0.2}s`,
                    display: 'inline-block'
                  }}>⭐</span>
                ))}
              </div>
              <p style={{
                color: '#0f172a',
                fontSize: '0.95rem',
                marginBottom: '0.5rem',
                fontWeight: '600'
              }}>
                TrustScore 4.9 | 2500+ avis
              </p>
              <p style={{
                color: '#64748b',
                fontSize: '0.9rem',
                lineHeight: '1.5'
              }}>
                Patients satisfaits de nos services médicaux de qualité
              </p>
            </div>
          </div>

          {/* COLONNE DROITE - CONTENU */}
          <div style={{
            display: 'flex',
            flexDirection: 'column',
            gap: '1rem'
          }}>
            {/* Header */}
            <div style={{
              opacity: isVisible ? 1 : 0,
              transform: isVisible ? 'translateY(0)' : 'translateY(-30px)',
              transition: 'opacity 0.6s ease-out 0.2s, transform 0.6s ease-out 0.2s'
            }}>
              <span style={{
                display: 'inline-block',
                background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
                WebkitBackgroundClip: 'text',
                WebkitTextFillColor: 'transparent',
                backgroundClip: 'text',
                fontWeight: '600',
                fontSize: '1.9rem',
                marginBottom: '0.75rem',
                textTransform: 'uppercase',
                letterSpacing: '0.1em',
                animation: 'shimmerText 3s ease-in-out infinite'
              }}>
                À Propos de Nous
              </span>

              <h2 style={{
                fontSize: '2.8rem',
                fontWeight: 'bold',
                color: '#0f172a',
                lineHeight: '1.2',
                marginBottom: '1.5rem',
                opacity: isVisible ? 1 : 0,
                transform: isVisible ? 'scale(1)' : 'scale(0.9)',
                transition: 'opacity 0.8s ease-out 0.3s, transform 0.8s ease-out 0.3s'
              }}>
                Excellente Qualité de Soins Médicaux
              </h2>

              <p style={{
                fontSize: '1.5rem',
                color: '#64748b',
                lineHeight: '1.8',
                marginBottom: '2rem',
                opacity: isVisible ? 1 : 0,
                transition: 'opacity 0.8s ease-out 0.4s'
              }}>
                CMO VISTAMD est engagée à fournir des services de soins médicaux exceptionnels avec un accent particulier sur le bien-être des patients en offrant des soins immédiats et un accompagnement durable . Nous mettons à votre disposition un transport médicalisé 24/7, des équipements de pointe et une expertise médicale de qualité
              </p>
            </div>

            {/* Features 2x2 avec animations améliorées */}
            <div style={{
              display: 'grid',
              gridTemplateColumns: '1fr 1fr',
              gap: '1.25rem',
              marginBottom: '2rem'
            }}>
              {features.map((feature, index) => (
                <div
                  key={index}
                  style={{
                    padding: '1.25rem',
                    background: hoveredFeature === index 
                      ? 'linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(16, 185, 129, 0.05))'
                      : 'rgba(34, 197, 94, 0.05)',
                    border: hoveredFeature === index 
                      ? '2px solid #22c55e' 
                      : '1px solid rgba(34, 197, 94, 0.2)',
                    borderRadius: '0.75rem',
                    transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)',
                    opacity: isVisible ? 1 : 0,
                    transform: isVisible ? 'translateY(0)' : 'translateY(30px)',
                    transitionDelay: `${0.4 + index * 0.1}s`,
                    cursor: 'pointer',
                    position: 'relative',
                    overflow: 'hidden'
                  }}
                  onMouseEnter={(e) => {
                    setHoveredFeature(index)
                    e.currentTarget.style.transform = 'translateY(-8px) scale(1.02)'
                    e.currentTarget.style.boxShadow = '0 15px 40px rgba(34, 197, 94, 0.15)'
                  }}
                  onMouseLeave={(e) => {
                    setHoveredFeature(null)
                    e.currentTarget.style.transform = 'translateY(0) scale(1)'
                    e.currentTarget.style.boxShadow = 'none'
                  }}
                >
                  {/* Effet de brillance au survol */}
                  <div style={{
                    position: 'absolute',
                    top: 0,
                    left: hoveredFeature === index ? '0' : '-100%',
                    width: '100%',
                    height: '100%',
                    background: 'linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent)',
                    transition: 'left 0.6s ease',
                    pointerEvents: 'none'
                  }}></div>

                  <div style={{
                    fontSize: '2rem',
                    marginBottom: '0.75rem',
                    display: 'inline-block',
                    animation: hoveredFeature === index ? 'bounce 0.6s ease' : 'none'
                  }}>
                    {feature.icon}
                  </div>
                  <div style={{
                    fontWeight: '600',
                    color: '#0f172a',
                    marginBottom: '0.5rem',
                    fontSize: '1.5rem',
                    display: 'flex',
                    alignItems: 'center',
                    gap: '0.5rem'
                  }}>
                    <span style={{ 
                      color: '#22c55e',
                      transform: hoveredFeature === index ? 'scale(1.2)' : 'scale(1)',
                      transition: 'transform 0.3s ease'
                    }}>✓</span>
                    {feature.title}
                  </div>
                  <p style={{
                    color: '#64748b',
                    fontSize: '1.3rem',
                    lineHeight: '1.5'
                  }}>
                    {feature.description}
                  </p>
                </div>
              ))}
            </div>

            {/* Stats 2x2 avec compteur animé */}
            <div style={{
              display: 'grid',
              gridTemplateColumns: '1fr 1fr',
              gap: '1.25rem',
              marginBottom: '2rem'
            }}>
              {stats.map((stat, index) => {
                const Icon = stat.icon
                return (
                  <div
                    key={index}
                    style={{
                      display: 'flex',
                      alignItems: 'center',
                      gap: '1rem',
                      padding: '1rem',
                      background: hoveredStat === index ? 'white' : '#f8fafc',
                      border: hoveredStat === index ? '2px solid rgba(34, 197, 94, 0.3)' : '1px solid rgba(34, 197, 94, 0.1)',
                      borderRadius: '0.75rem',
                      transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)',
                      opacity: isVisible ? 1 : 0,
                      transform: isVisible ? 'translateY(0)' : 'translateY(30px)',
                      transitionDelay: `${0.6 + index * 0.1}s`,
                      cursor: 'pointer',
                      position: 'relative',
                      overflow: 'hidden'
                    }}
                    onMouseEnter={(e) => {
                      setHoveredStat(index)
                      e.currentTarget.style.transform = 'translateX(10px) scale(1.05)'
                      e.currentTarget.style.boxShadow = '0 10px 30px rgba(34, 197, 94, 0.15)'
                    }}
                    onMouseLeave={(e) => {
                      setHoveredStat(null)
                      e.currentTarget.style.transform = 'translateX(0) scale(1)'
                      e.currentTarget.style.boxShadow = 'none'
                    }}
                  >
                    {/* Effet de vague au survol */}
                    <div style={{
                      position: 'absolute',
                      top: '50%',
                      left: '50%',
                      width: hoveredStat === index ? '200%' : '0%',
                      height: hoveredStat === index ? '200%' : '0%',
                      background: 'radial-gradient(circle, rgba(34, 197, 94, 0.1) 0%, transparent 70%)',
                      transform: 'translate(-50%, -50%)',
                      transition: 'all 0.6s ease',
                      borderRadius: '50%',
                      pointerEvents: 'none'
                    }}></div>

                    <div style={{
                      width: '50px',
                      height: '50px',
                      background: `linear-gradient(135deg, ${stat.color}, ${stat.color}dd)`,
                      borderRadius: '0.5rem',
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                      color: ' #1075B9',
                      transition: 'all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)',
                      transform: hoveredStat === index ? 'scale(1.15) rotate(10deg)' : 'scale(1)',
                      flexShrink: 0,
                      boxShadow: hoveredStat === index ? `0 10px 25px ${stat.color}40` : 'none'
                    }}>
                      <Icon size={24} style={{
                        animation: hoveredStat === index ? 'iconBounce 0.6s ease' : 'none'
                      }} />
                    </div>
                    <div style={{ position: 'relative', zIndex: 1 }}>
                      <div style={{
                        fontWeight: 'bold',
                        color: '#0f172a',
                        fontSize: '1.5rem',
                        animation: hoveredStat === index ? 'numberPop 0.5s ease' : 'none'
                      }}>
                        {stat.value}
                      </div>
                      <div style={{
                        color: '#64748b',
                        fontSize: '1.2rem'
                      }}>
                        {stat.label}
                      </div>
                    </div>
                  </div>
                )
              })}
            </div>
          </div>
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

        @keyframes slideInLeft {
          from {
            opacity: 0;
            transform: translateX(-50px);
          }
          to {
            opacity: 1;
            transform: translateX(0);
          }
        }

        @keyframes fadeIn {
          from {
            opacity: 0;
          }
          to {
            opacity: 1;
          }
        }

        @keyframes fadeInScale {
          from {
            opacity: 0;
            transform: scale(0.9);
          }
          to {
            opacity: 1;
            transform: scale(1);
          }
        }

        @keyframes float {
          0%, 100% {
            transform: translateY(0px);
          }
          50% {
            transform: translateY(10px);
          }
        }

        @keyframes floatImage {
          0%, 100% {
            transform: translateY(0px);
          }
          50% {
            transform: translateY(-15px);
          }
        }

        @keyframes floatSlow {
          0%, 100% {
            transform: translateY(0px);
          }
          50% {
            transform: translateY(8px);
          }
        }

        @keyframes pulse {
          0%, 100% {
            opacity: 1;
            transform: scale(1);
          }
          50% {
            opacity: 0.9;
            transform: scale(1.05);
          }
        }

        @keyframes blink {
          0%, 100% {
            opacity: 1;
          }
          50% {
            opacity: 0.3;
          }
        }

        @keyframes rotateBorder {
          0% {
            background-position: 0% 50%;
          }
          50% {
            background-position: 100% 50%;
          }
          100% {
            background-position: 0% 50%;
          }
        }

        @keyframes shimmerText {
          0%, 100% {
            background-position: 0% 50%;
          }
          50% {
            background-position: 100% 50%;
          }
        }

        @keyframes starBounce {
          0%, 100% {
            transform: translateY(0) scale(1);
          }
          50% {
            transform: translateY(-5px) scale(1.1);
          }
        }

        @keyframes bounce {
          0%, 100% {
            transform: translateY(0);
          }
          25% {
            transform: translateY(-10px);
          }
          50% {
            transform: translateY(0);
          }
          75% {
            transform: translateY(-5px);
          }
        }

        @keyframes iconBounce {
          0%, 100% {
            transform: scale(1);
          }
          50% {
            transform: scale(1.2);
          }
        }

        @keyframes numberPop {
          0% {
            transform: scale(1);
          }
          50% {
            transform: scale(1.2);
          }
          100% {
            transform: scale(1);
          }
        }

        @keyframes particleFloat {
          0%, 100% {
            transform: translateY(0) translateX(0);
            opacity: 0.4;
          }
          25% {
            opacity: 0.7;
          }
          50% {
            transform: translateY(-20px) translateX(10px);
            opacity: 0.4;
          }
          75% {
            opacity: 0.7;
          }
        }

        @media (max-width: 1024px) {
          .rts-about-area .row {
            grid-template-columns: 1fr !important;
          }
        }
      `}</style>
    </div>
  )
}

export default AboutSection