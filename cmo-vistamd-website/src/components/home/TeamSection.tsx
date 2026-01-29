// src/components/home/TeamSection.tsx
import { useState, useEffect, useRef } from 'react'
import { ChevronLeft, ChevronRight, Users, Award, Heart } from 'lucide-react'

const TeamSection = () => {
  const [currentIndex, setCurrentIndex] = useState(0)
  const [isAutoplay, setIsAutoplay] = useState(true)
  const [isVisible, setIsVisible] = useState(false)
  const [hoveredMember, setHoveredMember] = useState<number | null>(null)
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

  const teamMembers = [
    {
      id: 1,
      name: 'Dr. Diakité Moustapha',
      role: 'Directeur Général',
      speciality: 'Gestion & Administration',
      image: '/assets/images/team/Docteur.jpg',
      social: {
        facebook: '#',
        linkedin: '#',
        twitter: '#'
      }
    },
    {
      id: 2,
      name: 'Dr. Kouassi Marie',
      role: 'Médecin Chef',
      speciality: 'Médecine Générale',
      image: '/assets/images/team/02.jpg',
      social: {
        facebook: '#',
        linkedin: '#',
        twitter: '#'
      }
    },
    {
      id: 3,
      name: 'Dr. Traoré Ahmed',
      role: 'Responsable Urgences',
      speciality: 'Médecine d\'Urgence',
      image: '/assets/images/team/03.jpg',
      social: {
        facebook: '#',
        linkedin: '#',
        twitter: '#'
      }
    },
  
  ]

  // Auto-slide
  useEffect(() => {
    if (!isAutoplay) return
    const timer = setInterval(() => {
      setCurrentIndex((prev) => (prev + 1) % teamMembers.length)
    }, 4000)
    return () => clearInterval(timer)
  }, [isAutoplay, teamMembers.length])

  const nextSlide = () => {
    setCurrentIndex((prev) => (prev + 1) % teamMembers.length)
    setIsAutoplay(false)
  }

  const prevSlide = () => {
    setCurrentIndex((prev) => (prev - 1 + teamMembers.length) % teamMembers.length)
    setIsAutoplay(false)
  }

  // Affiche 3 membres à la fois
  const visibleMembers = [
    teamMembers[currentIndex],
    teamMembers[(currentIndex + 1) % teamMembers.length],
    teamMembers[(currentIndex + 2) % teamMembers.length]
  ]

  return (
    <div 
      ref={sectionRef}
      id='team' 
      className="rts-team-area rts-section-gap" 
      style={{
        background: '#f8fafc',
        padding: '100px 0',
        position: 'relative',
        overflow: 'hidden'
      }}
    >
      {/* Éléments de décoration animés */}
      <div style={{
        position: 'absolute',
        top: '10%',
        left: '-8%',
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
        right: '-8%',
        width: '400px',
        height: '400px',
        background: 'radial-gradient(circle, rgba(16, 117, 185, 0.08) 0%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(60px)',
        pointerEvents: 'none',
        animation: 'float 12s ease-in-out infinite 2s'
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
              top: `${15 + Math.random() * 70}%`,
              left: `${10 + Math.random() * 80}%`,
              opacity: 0.4,
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
          maxWidth: '800px',
          margin: '0 auto 4rem'
        }}>
          <div style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '0.5rem',
            marginBottom: '1rem',
            opacity: isVisible ? 1 : 0,
            transform: isVisible ? 'translateY(0)' : 'translateY(-30px)',
            transition: 'opacity 0.6s ease-out 0.2s, transform 0.6s ease-out 0.2s'
          }}>
            <Users 
              size={24} 
              style={{ 
                color: '#22c55e',
                animation: isVisible ? 'pulse 2s ease-in-out infinite' : 'none'
              }} 
            />
            <span style={{
              background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
              WebkitBackgroundClip: 'text',
              WebkitTextFillColor: 'transparent',
              backgroundClip: 'text',
              fontWeight: '600',
              fontSize: '1rem',
              textTransform: 'uppercase',
              letterSpacing: '0.1em'
            }}>
              Notre Équipe
            </span>
          </div>

          <h2 style={{
            fontSize: '3rem',
            fontWeight: 'bold',
            color: '#0f172a',
            lineHeight: '1.2',
            marginBottom: '1.5rem',
            opacity: isVisible ? 1 : 0,
            transform: isVisible ? 'scale(1)' : 'scale(0.9)',
            transition: 'opacity 0.6s ease-out 0.3s, transform 0.6s ease-out 0.3s'
          }}>
            Découvrez Notre Équipe <br />
            <span style={{
              background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
              WebkitBackgroundClip: 'text',
              WebkitTextFillColor: 'transparent',
              backgroundClip: 'text'
            }}>
              d'Experts Médicaux
            </span>
          </h2>

          <p style={{
            fontSize: '1.15rem',
            color: '#64748b',
            lineHeight: '1.8',
            opacity: isVisible ? 1 : 0,
            transition: 'opacity 0.6s ease-out 0.4s'
          }}>
            Notre équipe de professionnels dévoués et hautement qualifiés est au cœur de notre engagement à fournir des soins médicaux exceptionnels
          </p>
        </div>

        {/* Team Members Grid */}
        <div style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))',
          gap: '2rem',
          marginBottom: '3rem',
          opacity: isVisible ? 1 : 0,
          transform: isVisible ? 'translateY(0)' : 'translateY(50px)',
          transition: 'opacity 0.6s ease-out 0.5s, transform 0.6s ease-out 0.5s'
        }}>
          {visibleMembers.map((member, index) => (
            <div
              key={member.id}
              style={{
                background: '#ffffff',
                borderRadius: '1rem',
                overflow: 'hidden',
                transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)',
                boxShadow: '0 10px 30px rgba(0, 0, 0, 0.08)',
                position: 'relative',
                opacity: isVisible ? 1 : 0,
                transform: isVisible ? 'scale(1)' : 'scale(0.9)',
                transitionDelay: `${0.6 + index * 0.1}s`
              }}
              onMouseEnter={() => setHoveredMember(index)}
              onMouseLeave={() => setHoveredMember(null)}
            >
              {/* Image Container */}
              <div style={{
                position: 'relative',
                height: '580px',
                overflow: 'hidden'
              }}>
                <img
                  src={member.image}
                  alt={member.name}
                  style={{
                    width: '100%',
                    height: '100%',
                    objectFit: 'cover',
                    transition: 'all 0.5s ease',
                    transform: hoveredMember === index ? 'scale(1.1)' : 'scale(1)'
                  }}
                />

                {/* Overlay gradient au survol */}
                <div style={{
                  position: 'absolute',
                  top: 0,
                  left: 0,
                  right: 0,
                  bottom: 0,
                  background: 'linear-gradient(180deg, transparent 0%, rgba(34, 197, 94, 0.8) 100%)',
                  opacity: hoveredMember === index ? 1 : 0,
                  transition: 'opacity 0.3s ease'
                }}></div>

                {/* Badge spécialité */}
                <div style={{
                  position: 'absolute',
                  top: '15px',
                  right: '15px',
                  background: 'rgba(34, 197, 94, 0.95)',
                  color: 'white',
                  padding: '0.5rem 1rem',
                  borderRadius: '2rem',
                  fontSize: '0.8rem',
                  fontWeight: 'bold',
                  display: 'flex',
                  alignItems: 'center',
                  gap: '0.3rem',
                  boxShadow: '0 4px 12px rgba(34, 197, 94, 0.4)',
                  opacity: hoveredMember === index ? 1 : 0,
                  transform: hoveredMember === index ? 'translateY(0) scale(1)' : 'translateY(-10px) scale(0.8)',
                  transition: 'all 0.3s ease'
                }}>
                  <Award size={14} />
                  Expert
                </div>

                {/* Social Links - Visible au survol */}
                <div style={{
                  position: 'absolute',
                  bottom: '20px',
                  left: '50%',
                  transform: hoveredMember === index ? 'translate(-50%, 0)' : 'translate(-50%, 20px)',
                  opacity: hoveredMember === index ? 1 : 0,
                  transition: 'all 0.3s ease',
                  display: 'flex',
                  gap: '0.75rem',
                  zIndex: 2
                }}>
                  {['facebook', 'linkedin', 'twitter'].map((platform, i) => (
                    <a
                      key={platform}
                      href={member.social[platform as keyof typeof member.social]}
                      style={{
                        width: '40px',
                        height: '40px',
                        background: 'white',
                        borderRadius: '50%',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        color: '#22c55e',
                        fontSize: '1.1rem',
                        transition: 'all 0.3s ease',
                        textDecoration: 'none',
                        boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)',
                        animation: hoveredMember === index ? `bounceIn 0.5s ease ${i * 0.1}s both` : 'none'
                      }}
                      onMouseEnter={(e) => {
                        e.currentTarget.style.background = 'linear-gradient(135deg, #22c55e, #10b981)'
                        e.currentTarget.style.color = 'white'
                        e.currentTarget.style.transform = 'translateY(-3px) scale(1.1)'
                      }}
                      onMouseLeave={(e) => {
                        e.currentTarget.style.background = 'white'
                        e.currentTarget.style.color = '#22c55e'
                        e.currentTarget.style.transform = 'translateY(0) scale(1)'
                      }}
                    >
                      {platform === 'facebook' && 'f'}
                      {platform === 'linkedin' && 'in'}
                      {platform === 'twitter' && '𝕏'}
                    </a>
                  ))}
                </div>
              </div>

              {/* Content */}
              <div style={{
                padding: '3rem',
                position: 'relative'
              }}>
                {/* Icône décorative */}
                <div style={{
                  position: 'absolute',
                  top: '-30px',
                  right: '20px',
                  width: '60px',
                  height: '60px',
                  background: 'linear-gradient(135deg, #22c55e, #10b981)',
                  borderRadius: '50%',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  color: 'white',
                  boxShadow: '0 10px 25px rgba(34, 197, 94, 0.3)',
                  transition: 'all 0.3s ease',
                  transform: hoveredMember === index ? 'scale(1.1) rotate(10deg)' : 'scale(1)'
                }}>
                  <Heart size={28} />
                </div>

                <h3 style={{
                  fontSize: '2rem',
                  fontWeight: 'bold',
                  color: hoveredMember === index ? '#22c55e' : '#0f172a',
                  marginBottom: '0.5rem',
                  transition: 'color 0.3s ease'
                }}>
                  {member.name}
                </h3>

                <p style={{
                  fontSize: '1.5rem',
                  color: '#22c55e',
                  fontWeight: '600',
                  marginBottom: '0.5rem'
                }}>
                  {member.role}
                </p>

                <p style={{
                  fontSize: '1.2rem',
                  color: '#64748b',
                  marginBottom: '0'
                }}>
                  {member.speciality}
                </p>
              </div>
            </div>
          ))}
        </div>

        {/* Navigation Controls */}
        <div style={{
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          gap: '2rem',
          opacity: isVisible ? 1 : 0,
          transform: isVisible ? 'translateY(0)' : 'translateY(30px)',
          transition: 'opacity 0.6s ease-out 0.9s, transform 0.6s ease-out 0.9s'
        }}>
          {/* Previous Button */}
          <button
            onClick={prevSlide}
            style={{
              width: '50px',
              height: '50px',
              background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
              border: 'none',
              borderRadius: '50%',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              color: 'white',
              cursor: 'pointer',
              transition: 'all 0.3s ease',
              boxShadow: '0 10px 25px rgba(34, 197, 94, 0.2)'
            }}
            onMouseEnter={(e) => {
              e.currentTarget.style.transform = 'scale(1.15) rotate(-10deg)'
              e.currentTarget.style.boxShadow = '0 15px 35px rgba(34, 197, 94, 0.4)'
            }}
            onMouseLeave={(e) => {
              e.currentTarget.style.transform = 'scale(1) rotate(0deg)'
              e.currentTarget.style.boxShadow = '0 10px 25px rgba(34, 197, 94, 0.2)'
            }}
          >
            <ChevronLeft size={24} />
          </button>

          {/* Dots Navigation */}
          <div style={{
            display: 'flex',
            gap: '0.5rem',
            alignItems: 'center'
          }}>
            {teamMembers.map((_, index) => (
              <button
                key={index}
                onClick={() => setCurrentIndex(index)}
                style={{
                  width: currentIndex === index ? '30px' : '10px',
                  height: '10px',
                  background: currentIndex === index 
                    ? 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)' 
                    : 'rgba(34, 197, 94, 0.2)',
                  border: 'none',
                  borderRadius: '50px',
                  cursor: 'pointer',
                  transition: 'all 0.3s ease',
                  boxShadow: currentIndex === index ? '0 4px 12px rgba(34, 197, 94, 0.3)' : 'none'
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
              width: '50px',
              height: '50px',
              background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
              border: 'none',
              borderRadius: '50%',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              color: 'white',
              cursor: 'pointer',
              transition: 'all 0.3s ease',
              boxShadow: '0 10px 25px rgba(34, 197, 94, 0.2)'
            }}
            onMouseEnter={(e) => {
              e.currentTarget.style.transform = 'scale(1.15) rotate(10deg)'
              e.currentTarget.style.boxShadow = '0 15px 35px rgba(34, 197, 94, 0.4)'
            }}
            onMouseLeave={(e) => {
              e.currentTarget.style.transform = 'scale(1) rotate(0deg)'
              e.currentTarget.style.boxShadow = '0 10px 25px rgba(34, 197, 94, 0.2)'
            }}
          >
            <ChevronRight size={24} />
          </button>
        </div>
      </div>

      <style>{`
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
            transform: scale(1.1);
          }
        }

        @keyframes bounceIn {
          0% {
            opacity: 0;
            transform: translateY(20px) scale(0.8);
          }
          50% {
            transform: translateY(-5px) scale(1.1);
          }
          100% {
            opacity: 1;
            transform: translateY(0) scale(1);
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
      `}</style>
    </div>
  )
}

export default TeamSection