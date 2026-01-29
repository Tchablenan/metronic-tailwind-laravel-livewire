// src/components/home/HeroSection.tsx
import { useState, useEffect } from 'react'
import type { AppointmentFormData } from '@/types/services'
import type { ApiResponse } from '@/types/common'

const HeroSection = () => {
  const [formData, setFormData] = useState<AppointmentFormData>({
    first_name: '',
    last_name: '',
    email: '',
    phone_number: '',
    service_type: '',
    message: '',
    preferred_date: '',
    preferred_time: '10:00',
    urgency: 'medium'
  })

  const [isSubmitting, setIsSubmitting] = useState(false)
  const [submitStatus, setSubmitStatus] = useState<{
    type: 'success' | 'error' | null
    message: string
  }>({ type: null, message: '' })

  const [currentSlide, setCurrentSlide] = useState(0)

  // URL de l'API Laravel
  const API_URL = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api'

  // URL de l'image de fond (optionnelle)
  const backgroundImage = '/assets/doctor-reading.jpg'

  // Auto-rotate slides
  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % 3)
    }, 8000) // Change slide toutes les 8 secondes
    return () => clearInterval(interval)
  }, [])

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setIsSubmitting(true)
    setSubmitStatus({ type: null, message: '' })

    try {
      const response = await fetch(`${API_URL}/service-requests`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(formData),
      })

      const data: ApiResponse = await response.json()

      if (response.ok && data.success) {
        setSubmitStatus({
          type: 'success',
          message: data.message || 'Votre demande a été envoyée avec succès ! Nous vous contacterons bientôt.'
        })
        
        // Réinitialiser le formulaire
        setFormData({
          first_name: '',
          last_name: '',
          email: '',
          phone_number: '',
          service_type: '',
          message: '',
          preferred_date: '',
          preferred_time: '10:00',
          urgency: 'medium'
        })

        // Masquer le message après 5 secondes
        setTimeout(() => {
          setSubmitStatus({ type: null, message: '' })
        }, 5000)
      } else {
        // Erreurs de validation
        if (data.errors) {
          const firstError = Object.values(data.errors)[0][0]
          setSubmitStatus({
            type: 'error',
            message: firstError
          })
        } else {
          setSubmitStatus({
            type: 'error',
            message: data.message || 'Une erreur est survenue. Veuillez réessayer.'
          })
        }
      }
    } catch (error) {
      console.error('Erreur lors de l\'envoi:', error)
      setSubmitStatus({
        type: 'error',
        message: 'Impossible de contacter le serveur. Veuillez réessayer plus tard.'
      })
    } finally {
      setIsSubmitting(false)
    }
  }

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target
    setFormData(prev => ({
      ...prev,
      [name]: value
    }))
  }

  return (
    <div 
      id='home' 
      data-section='home'
      className="rts-banner-area-three bg_image rts-wsection-gap" 
      style={{
      position: 'relative',
      overflow: 'hidden',
      minHeight: '100vh',
      display: 'flex',
      alignItems: 'center',
      padding: '80px 20px 40px',
      background: 'linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'
    }}>
      {/* Image de fond avec effet Ken Burns */}
      {backgroundImage && (
        <div style={{
          position: 'absolute',
          top: 0,
          left: 0,
          width: '100%',
          height: '100%',
          backgroundImage: `url(${backgroundImage})`,
          backgroundSize: 'cover',
          backgroundPosition: 'center',
          backgroundRepeat: 'no-repeat',
          zIndex: 0,
          animation: 'kenBurns 30s ease-in-out infinite alternate',
          opacity: 0.15
        }}></div>
      )}

      {/* Gradient overlay principal - plus sobre */}
      <div style={{
        position: 'absolute',
        top: 0,
        left: 0,
        width: '100%',
        height: '100%',
        background: 'linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 41, 59, 0.95) 100%)',
        zIndex: 1
      }}></div>

      {/* Orbes lumineux animés - discrets */}
      <div style={{
        position: 'absolute',
        top: '-10%',
        right: '-5%',
        width: '400px',
        height: '400px',
        background: 'radial-gradient(circle, rgba(34, 197, 94, 0.08) 0%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(60px)',
        pointerEvents: 'none',
        animation: 'float 15s ease-in-out infinite',
        zIndex: 2
      }}></div>
      <div style={{
        position: 'absolute',
        bottom: '-5%',
        left: '-5%',
        width: '400px',
        height: '400px',
        background: 'radial-gradient(circle, rgba(34, 197, 94, 0.06) 0%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(60px)',
        pointerEvents: 'none',
        animation: 'float 20s ease-in-out infinite 2s',
        zIndex: 2
      }}></div>

      <div style={{ 
        maxWidth: '1400px', 
        margin: '0 auto', 
        width: '100%',
        position: 'relative', 
        zIndex: 3,
        height: '100%'
      }}>
        {/* Carousel Container */}
        <div style={{
          position: 'relative',
          width: '100%',
          height: '100%',
          overflow: 'hidden'
        }}>
          {/* SLIDE 1 - URGENCES */}
          <div style={{
            position: 'absolute',
            width: '100%',
            height: '100%',
            display: currentSlide === 0 ? 'flex' : 'none',
            opacity: currentSlide === 0 ? 1 : 0,
            transition: 'opacity 0.8s ease-in-out',
            alignItems: 'center',
            justifyContent: 'center'
          }}>
            <div style={{
              textAlign: 'center',
              animation: currentSlide === 0 ? 'slideInDown 0.8s ease-out' : 'none'
            }}>
              <div style={{
                fontSize: 'clamp(1rem, 2vw, 1.5rem)',
                color: '#ef4444',
                fontWeight: '700',
                marginBottom: '1rem'
              }}>
                🚨 SERVICE D'URGENCES
              </div>
              <h2 style={{
                fontSize: 'clamp(2.5rem, 10vw, 5rem)',
                fontWeight: 'black',
                marginBottom: '1rem',
                color: 'white',
                lineHeight: '1.2'
              }}>
                Ouvert 24/7
              </h2>
              <p style={{
                fontSize: 'clamp(1rem, 3vw, 1.8rem)',
                color: '#cbd5e1',
                marginBottom: '2rem',
                maxWidth: '600px',
                margin: '0 auto 2rem',
                lineHeight: '1.6'
              }}>
                Urgences médicales traitées immédiatement par notre équipe de spécialistes disponibles à tout moment
              </p>
              <a
                href="tel:+2250720552099"
                style={{
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '1rem',
                  padding: '1.2rem 2.5rem',
                  background: '#ef4444',
                  color: 'white',
                  borderRadius: '0.75rem',
                  textDecoration: 'none',
                  fontWeight: 'bold',
                  fontSize: '1.3rem',
                  cursor: 'pointer',
                  transition: 'all 0.3s ease',
                  boxShadow: '0 10px 30px rgba(239, 68, 68, 0.4)',
                  border: 'none',
                  animation: 'pulse 2s ease-in-out infinite'
                }}
                onMouseEnter={(e) => {
                  e.currentTarget.style.transform = 'translateY(-5px) scale(1.05)'
                  e.currentTarget.style.boxShadow = '0 20px 50px rgba(239, 68, 68, 0.6)'
                }}
                onMouseLeave={(e) => {
                  e.currentTarget.style.transform = 'translateY(0) scale(1)'
                  e.currentTarget.style.boxShadow = '0 10px 30px rgba(239, 68, 68, 0.4)'
                }}
              >
                📞 Appeler +225 07 205 520 99
              </a>
            </div>
          </div>

          {/* SLIDE 2 - PRÉSENTATION */}
          <div style={{
            position: 'absolute',
            width: '100%',
            height: '100%',
            display: currentSlide === 1 ? 'flex' : 'none',
            opacity: currentSlide === 1 ? 1 : 0,
            transition: 'opacity 0.8s ease-in-out',
            alignItems: 'center',
            justifyContent: 'center'
          }}>
            <div style={{
              display: 'grid',
              gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))',
              gap: '2rem',
              width: '100%',
              animation: currentSlide === 1 ? 'slideInDown 0.8s ease-out' : 'none'
            }}>
              <div>
                <div style={{
                  display: 'inline-block',
                  padding: '0.75rem 1.5rem',
                  background: 'rgba(34, 197, 94, 0.15)',
                  border: '1px solid rgba(34, 197, 94, 0.4)',
                  borderRadius: '2rem',
                  marginBottom: '1.5rem'
                }}>
                  <span style={{
                    fontSize: '0.85rem',
                    color: '#22c55e',
                    fontWeight: '600'
                  }}>✓ Leader depuis 30 ans</span>
                </div>

                <h1 
                  style={{
                    fontSize: 'clamp(2rem, 8vw, 3.5rem)',
                    fontWeight: 'bold',
                    lineHeight: '1.2',
                    marginBottom: '1rem',
                    color: 'white'
                  }}
                >
                  CMO
                  <span style={{
                    display: 'block',
                    background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
                    WebkitBackgroundClip: 'text',
                    WebkitTextFillColor: 'transparent',
                    backgroundClip: 'text',
                    fontWeight: 'black',
                    fontSize: 'clamp(2rem, 10vw, 4.5rem)'
                  }}>
                    VISTAMD
                  </span>
                </h1>
                
                <p style={{
                  fontSize: 'clamp(1rem, 3vw, 1.5rem)',
                  color: '#cbd5e1',
                  marginBottom: '1.5rem',
                  fontWeight: '500',
                  lineHeight: '1.6'
                }}>
                  Excellence médicale, compassion sans limite
                </p>

                <p style={{
                  fontSize: 'clamp(0.9rem, 2.5vw, 1.1rem)',
                  color: '#94a3b8',
                  lineHeight: '1.7',
                  maxWidth: '600px'
                }}>
                  Établissement hospitalier pluridisciplinaire de référence. Soins immédiats et suivi à long terme avec une équipe médicale dédiée à votre bien-être.
                </p>
              </div>

              <div style={{
                display: 'grid',
                gridTemplateColumns: 'repeat(2, 1fr)',
                gap: '1rem',
                height: 'fit-content'
              }}>
                {[
                  { icon: '📋', number: '+65K', label: 'Consultations' },
                  { icon: '🛏️', number: '+6.5K', label: 'Hospitalisations' },
                  { icon: '🔬', number: '+300K', label: 'Analyses' },
                  { icon: '🚑', number: '+11K', label: 'Urgences' }
                ].map((stat, idx) => (
                  <div
                    key={idx}
                    style={{
                      padding: '1.5rem',
                      background: 'rgba(34, 197, 94, 0.1)',
                      border: '1px solid rgba(34, 197, 94, 0.3)',
                      borderRadius: '1rem',
                      textAlign: 'center',
                      transition: 'all 0.3s ease'
                    }}
                    onMouseEnter={(e) => {
                      e.currentTarget.style.background = 'rgba(34, 197, 94, 0.2)'
                      e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.5)'
                      e.currentTarget.style.transform = 'translateY(-5px)'
                    }}
                    onMouseLeave={(e) => {
                      e.currentTarget.style.background = 'rgba(34, 197, 94, 0.1)'
                      e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.3)'
                      e.currentTarget.style.transform = 'translateY(0)'
                    }}
                  >
                    <div style={{ fontSize: '2rem', marginBottom: '0.5rem' }}>{stat.icon}</div>
                    <div style={{ fontSize: '1.5rem', color: '#22c55e', fontWeight: 'bold', marginBottom: '0.25rem' }}>{stat.number}</div>
                    <div style={{ fontSize: '0.85rem', color: '#cbd5e1' }}>{stat.label}</div>
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* SLIDE 3 - FORMULAIRE */}
          <div style={{
            position: 'absolute',
            width: '100%',
            height: '100%',
            display: currentSlide === 2 ? 'flex' : 'none',
            opacity: currentSlide === 2 ? 1 : 0,
            transition: 'opacity 0.8s ease-in-out',
            alignItems: 'center',
            justifyContent: 'center'
          }}>
            <div style={{
              maxWidth: '600px',
              width: '100%',
              animation: currentSlide === 2 ? 'slideInRight 0.8s ease-out' : 'none'
            }}>
              <div style={{
                background: 'rgba(30, 41, 59, 0.8)',
                backdropFilter: 'blur(10px)',
                border: '1px solid rgba(34, 197, 94, 0.2)',
                borderRadius: '1.5rem',
                padding: '2.5rem',
                boxShadow: '0 25px 70px rgba(0, 0, 0, 0.4)',
                position: 'relative',
                overflow: 'hidden'
              }}
              onMouseEnter={(e) => {
                e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.4)'
                e.currentTarget.style.boxShadow = '0 30px 80px rgba(34, 197, 94, 0.2)'
              }}
              onMouseLeave={(e) => {
                e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.2)'
                e.currentTarget.style.boxShadow = '0 25px 70px rgba(0, 0, 0, 0.4)'
              }}>
                <div style={{
                  position: 'absolute',
                  top: 0,
                  left: 0,
                  width: '100%',
                  height: '1px',
                  background: 'linear-gradient(90deg, transparent, #22c55e, transparent)',
                }}></div>

                <div style={{ position: 'relative', zIndex: 1 }}>
                  <h3 style={{
                    fontSize: '2rem',
                    fontWeight: 'bold',
                    textAlign: 'center',
                    color: 'white',
                    marginBottom: '0.5rem',
                    background: 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
                    WebkitBackgroundClip: 'text',
                    WebkitTextFillColor: 'transparent',
                    backgroundClip: 'text'
                  }}>
                    Prenez un rendez-vous
                  </h3>
                  <p style={{ 
                    color: '#94a3b8', 
                    marginBottom: '2rem', 
                    fontSize: '0.95rem',
                    textAlign: 'center',
                    lineHeight: '1.5'
                  }}>
                    Réservez une consultation avec nos médecins spécialistes. Réponse dans les 24 heures.
                  </p>

                {/* Message de statut */}
                {submitStatus.type && (
                  <div style={{
                    padding: '0.75rem 1rem',
                    marginBottom: '1rem',
                    borderRadius: '0.5rem',
                    background: submitStatus.type === 'success' 
                      ? 'rgba(34, 197, 94, 0.1)' 
                      : 'rgba(239, 68, 68, 0.1)',
                    border: `1px solid ${submitStatus.type === 'success' ? '#22c55e' : '#ef4444'}`,
                    color: submitStatus.type === 'success' ? '#22c55e' : '#ef4444',
                    fontSize: '0.9rem',
                    textAlign: 'center',
                    animation: 'slideInDown 0.3s ease-out'
                  }}>
                    {submitStatus.message}
                  </div>
                )}
                
                <form onSubmit={handleSubmit}>
                  <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '0.75rem', marginBottom: '0.75rem' }}>
                    <input 
                      type="text" 
                      name="first_name"
                      placeholder="Prénom *" 
                      value={formData.first_name}
                      onChange={handleChange}
                      required
                      disabled={isSubmitting}
                      style={{
                        width: '100%',
                        padding: '0.75rem 1rem',
                        background: 'rgba(51, 65, 85, 0.5)',
                        border: '1px solid rgba(34, 197, 94, 0.3)',
                        borderRadius: '0.5rem',
                        color: 'white',
                        fontSize: '1.5rem',
                        transition: 'all 0.3s ease',
                        boxSizing: 'border-box'
                      }}
                      onFocus={(e) => {
                        e.currentTarget.style.borderColor = '#22c55e'
                        e.currentTarget.style.background = 'rgba(51, 65, 85, 0.8)'
                      }}
                      onBlur={(e) => {
                        e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.3)'
                        e.currentTarget.style.background = 'rgba(51, 65, 85, 0.5)'
                      }}
                    />

                    <input 
                      type="text" 
                      name="last_name"
                      placeholder="Nom *" 
                      value={formData.last_name}
                      onChange={handleChange}
                      required
                      disabled={isSubmitting}
                      style={{
                        width: '100%',
                        padding: '0.75rem 1rem',
                        background: 'rgba(51, 65, 85, 0.5)',
                        border: '1px solid rgba(34, 197, 94, 0.3)',
                        borderRadius: '0.5rem',
                        color: 'white',
                        fontSize: '1.5rem',
                        transition: 'all 0.3s ease',
                        boxSizing: 'border-box'
                      }}
                      onFocus={(e) => {
                        e.currentTarget.style.borderColor = '#22c55e'
                        e.currentTarget.style.background = 'rgba(51, 65, 85, 0.8)'
                      }}
                      onBlur={(e) => {
                        e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.3)'
                        e.currentTarget.style.background = 'rgba(51, 65, 85, 0.5)'
                      }}
                    />
                  </div>
                
                  <input 
                    type="email" 
                    name="email"
                    placeholder="Votre Email *" 
                    value={formData.email}
                    onChange={handleChange}
                    required
                    disabled={isSubmitting}
                    style={{
                      width: '100%',
                      padding: '0.75rem 1rem',
                      marginBottom: '0.75rem',
                      background: 'rgba(51, 65, 85, 0.5)',
                      border: '1px solid rgba(34, 197, 94, 0.3)',
                      borderRadius: '0.5rem',
                      color: 'white',
                      fontSize: '1.5rem',
                      transition: 'all 0.3s ease',
                      boxSizing: 'border-box'
                    }}
                    onFocus={(e) => {
                      e.currentTarget.style.borderColor = '#22c55e'
                      e.currentTarget.style.background = 'rgba(51, 65, 85, 0.8)'
                    }}
                    onBlur={(e) => {
                      e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.3)'
                      e.currentTarget.style.background = 'rgba(51, 65, 85, 0.5)'
                    }}
                  />
                
                  <input 
                    type="tel" 
                    name="phone_number"
                    placeholder="Votre Téléphone *" 
                    value={formData.phone_number}
                    onChange={handleChange}
                    required
                    disabled={isSubmitting}
                    style={{
                      width: '100%',
                      padding: '0.75rem 1rem',
                      marginBottom: '0.75rem',
                      background: 'rgba(51, 65, 85, 0.5)',
                      border: '1px solid rgba(34, 197, 94, 0.3)',
                      borderRadius: '0.5rem',
                      color: 'white',
                      fontSize: '1.5rem',
                      transition: 'all 0.3s ease',
                      boxSizing: 'border-box'
                    }}
                    onFocus={(e) => {
                      e.currentTarget.style.borderColor = '#22c55e'
                      e.currentTarget.style.background = 'rgba(51, 65, 85, 0.8)'
                    }}
                    onBlur={(e) => {
                      e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.3)'
                      e.currentTarget.style.background = 'rgba(51, 65, 85, 0.5)'
                    }}
                  />
                  
                  <select 
                    name="service_type"
                    value={formData.service_type}
                    onChange={handleChange}
                    required
                    disabled={isSubmitting}
                    style={{
                      width: '100%',
                      padding: '0.75rem 1rem',
                      marginBottom: '0.75rem',
                      background: 'rgba(51, 65, 85, 0.5)',
                      border: '1px solid rgba(34, 197, 94, 0.3)',
                      borderRadius: '0.5rem',
                      color: 'white',
                      fontSize: '1.5rem',
                      cursor: 'pointer',
                      boxSizing: 'border-box'
                    }}
                  >
                    <option value="">Type de service *</option>
                    <option value="appointment">Rendez-vous médical</option>
                    <option value="home_visit">Visite à domicile</option>
                    <option value="emergency">Urgence</option>
                    <option value="transport">Transport médicalisé</option>
                    <option value="consultation">Consultation</option>
                    <option value="other">Autre service</option>
                  </select>

                  <input 
                    type="text" 
                    name="message"
                    placeholder="Quelle est votre préoccupation?" 
                    value={formData.message}
                    onChange={handleChange}
                    disabled={isSubmitting}
                    style={{
                      width: '100%',
                      padding: '0.75rem 1rem',
                      marginBottom: '0.75rem',
                      background: 'rgba(51, 65, 85, 0.5)',
                      border: '1px solid rgba(34, 197, 94, 0.3)',
                      borderRadius: '0.5rem',
                      color: 'white',
                      fontSize: '1.5rem',
                      transition: 'all 0.3s ease',
                      boxSizing: 'border-box'
                    }}
                    onFocus={(e) => {
                      e.currentTarget.style.borderColor = '#22c55e'
                      e.currentTarget.style.background = 'rgba(51, 65, 85, 0.8)'
                    }}
                    onBlur={(e) => {
                      e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.3)'
                      e.currentTarget.style.background = 'rgba(51, 65, 85, 0.5)'
                    }}
                  />
                  
                  <div style={{
                    display: 'grid',
                    gridTemplateColumns: '1fr 1fr',
                    gap: '0.75rem',
                    marginBottom: '0.75rem'
                  }}>
                    <input 
                      type="date" 
                      name="preferred_date"
                      value={formData.preferred_date}
                      onChange={handleChange}
                      min={new Date().toISOString().split('T')[0]}
                      disabled={isSubmitting}
                      style={{
                        padding: '0.75rem 1rem',
                        background: 'rgba(51, 65, 85, 0.5)',
                        border: '1px solid rgba(34, 197, 94, 0.3)',
                        borderRadius: '0.5rem',
                        color: 'white',
                        fontSize: '1.5rem',
                        transition: 'all 0.3s ease',
                        boxSizing: 'border-box'
                      }}
                    />
                    
                    <select 
                      name="urgency"
                      value={formData.urgency}
                      onChange={handleChange}
                      disabled={isSubmitting}
                      style={{
                        padding: '0.75rem 1rem',
                        background: 'rgba(51, 65, 85, 0.5)',
                        border: '1px solid rgba(34, 197, 94, 0.3)',
                        borderRadius: '0.5rem',
                        color: 'white',
                        fontSize: '1.5rem',
                        cursor: 'pointer',
                        boxSizing: 'border-box'
                      }}
                    >
                      <option value="low">Urgence faible</option>
                      <option value="medium">Urgence moyenne</option>
                      <option value="high">Urgence élevée</option>
                    </select>
                  </div>

                  <select 
                    name="preferred_time"
                    value={formData.preferred_time}
                    onChange={handleChange}
                    disabled={isSubmitting}
                    style={{
                      width: '100%',
                      padding: '0.75rem 1rem',
                      marginBottom: '1rem',
                      background: 'rgba(51, 65, 85, 0.5)',
                      border: '1px solid rgba(34, 197, 94, 0.3)',
                      borderRadius: '0.5rem',
                      color: 'white',
                      fontSize: '1.5rem',
                      cursor: 'pointer',
                      boxSizing: 'border-box'
                    }}
                  >
                    <option value="10:00">Matin 10h00</option>
                    <option value="11:00">Matin 11h00</option>
                    <option value="14:00">Après-midi 14h00</option>
                    <option value="15:00">Après-midi 15h00</option>
                    <option value="17:00">Soir 17h00</option>
                  </select>
                  
                  <button 
                    type="submit"
                    disabled={isSubmitting}
                    style={{
                      width: '100%',
                      padding: '0.875rem',
                      background: isSubmitting 
                        ? 'rgba(34, 197, 94, 0.5)' 
                        : 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)',
                      color: 'white',
                      fontWeight: 'bold',
                      border: 'none',
                      borderRadius: '0.5rem',
                      fontSize: '1.5rem',
                      cursor: isSubmitting ? 'not-allowed' : 'pointer',
                      transition: 'all 0.3s ease',
                      boxShadow: '0 10px 30px rgba(34, 197, 94, 0.3)',
                      position: 'relative',
                      overflow: 'hidden',
                      opacity: isSubmitting ? 0.7 : 1
                    }}
                    onMouseEnter={(e) => {
                      if (!isSubmitting) {
                        e.currentTarget.style.transform = 'translateY(-2px) scale(1.02)'
                        e.currentTarget.style.boxShadow = '0 15px 40px rgba(34, 197, 94, 0.5)'
                      }
                    }}
                    onMouseLeave={(e) => {
                      if (!isSubmitting) {
                        e.currentTarget.style.transform = 'translateY(0) scale(1)'
                        e.currentTarget.style.boxShadow = '0 10px 30px rgba(34, 197, 94, 0.3)'
                      }
                    }}
                  >
                    <span style={{ position: 'relative', zIndex: 1 }}>
                      {isSubmitting ? 'Envoi en cours...' : 'Prendre Rendez-vous'}
                    </span>
                    {!isSubmitting && (
                      <span style={{
                        position: 'absolute',
                        top: 0,
                        left: '-100%',
                        width: '100%',
                        height: '100%',
                        background: 'linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent)',
                        animation: 'shimmerButton 2s ease-in-out infinite'
                      }}></span>
                    )}
                  </button>
                </form>

                <p style={{
                  fontSize: '0.9rem',
                  color: '#64748b',
                  textAlign: 'center',
                  marginTop: '1rem'
                }}>
                  Vos données sont sécurisées • Réponse sous 24h
                </p>
              </div>
            </div>
          </div>

          {/* Navigation Controls */}
          <div style={{
            position: 'absolute',
            bottom: '2rem',
            left: '50%',
            transform: 'translateX(-50%)',
            display: 'flex',
            alignItems: 'center',
            gap: '2rem',
            zIndex: 10
          }}>
          {/* Bouton Précédent */}
          <button
            onClick={() => setCurrentSlide((prev) => (prev - 1 + 3) % 3)}
            style={{
              width: '50px',
              height: '50px',
              borderRadius: '50%',
              background: 'rgba(34, 197, 94, 0.2)',
              border: '2px solid rgba(34, 197, 94, 0.5)',
              color: '#22c55e',
              cursor: 'pointer',
              fontSize: '1.5rem',
              transition: 'all 0.3s ease',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              fontWeight: 'bold'
            }}
            onMouseEnter={(e) => {
              e.currentTarget.style.background = 'rgba(34, 197, 94, 0.3)'
              e.currentTarget.style.transform = 'scale(1.1)'
              e.currentTarget.style.boxShadow = '0 0 20px rgba(34, 197, 94, 0.4)'
            }}
            onMouseLeave={(e) => {
              e.currentTarget.style.background = 'rgba(34, 197, 94, 0.2)'
              e.currentTarget.style.transform = 'scale(1)'
              e.currentTarget.style.boxShadow = 'none'
            }}
          >
            ‹
          </button>

          {/* Indicateurs */}
          <div style={{
            display: 'flex',
            gap: '1rem',
            alignItems: 'center'
          }}>
            {[0, 1, 2].map((index) => (
              <button
                key={index}
                onClick={() => setCurrentSlide(index)}
                style={{
                  width: currentSlide === index ? '40px' : '12px',
                  height: '12px',
                  borderRadius: '50%',
                  background: currentSlide === index 
                    ? 'linear-gradient(135deg, #22c55e 0%, #10b981 100%)'
                    : 'rgba(34, 197, 94, 0.3)',
                  border: 'none',
                  cursor: 'pointer',
                  transition: 'all 0.3s ease'
                }}
                onMouseEnter={(e) => {
                  if (currentSlide !== index) {
                    e.currentTarget.style.background = 'rgba(34, 197, 94, 0.5)'
                  }
                }}
                onMouseLeave={(e) => {
                  if (currentSlide !== index) {
                    e.currentTarget.style.background = 'rgba(34, 197, 94, 0.3)'
                  }
                }}
              />
            ))}
          </div>

          {/* Bouton Suivant */}
          <button
            onClick={() => setCurrentSlide((prev) => (prev + 1) % 3)}
            style={{
              width: '50px',
              height: '50px',
              borderRadius: '50%',
              background: 'rgba(34, 197, 94, 0.2)',
              border: '2px solid rgba(34, 197, 94, 0.5)',
              color: '#22c55e',
              cursor: 'pointer',
              fontSize: '1.5rem',
              transition: 'all 0.3s ease',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              fontWeight: 'bold'
            }}
            onMouseEnter={(e) => {
              e.currentTarget.style.background = 'rgba(34, 197, 94, 0.3)'
              e.currentTarget.style.transform = 'scale(1.1)'
              e.currentTarget.style.boxShadow = '0 0 20px rgba(34, 197, 94, 0.4)'
            }}
            onMouseLeave={(e) => {
              e.currentTarget.style.background = 'rgba(34, 197, 94, 0.2)'
              e.currentTarget.style.transform = 'scale(1)'
              e.currentTarget.style.boxShadow = 'none'
            }}
          >
            ›
          </button>
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

        @keyframes slideInRight {
          from {
            opacity: 0;
            transform: translateX(50px);
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

        @keyframes float {
          0%, 100% {
            transform: translateY(0px);
          }
          50% {
            transform: translateY(20px);
          }
        }

        @keyframes floatSlow {
          0%, 100% {
            transform: translateY(0px);
          }
          50% {
            transform: translateY(10px);
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

        @keyframes textGradient {
          0%, 100% {
            background: linear-gradient(135deg, #22c55e 0%, #10b981 50%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
          }
          50% {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #22c55e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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

        @keyframes shimmer {
          0%, 100% {
            background-color: rgba(34, 197, 94, 0.1);
            border-color: rgba(34, 197, 94, 0.3);
          }
          50% {
            background-color: rgba(34, 197, 94, 0.2);
            border-color: rgba(34, 197, 94, 0.5);
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

        @keyframes glow {
          0%, 100% {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
          }
          50% {
            box-shadow: 0 20px 60px rgba(34, 197, 94, 0.2);
          }
        }

        @keyframes shimmerLine {
          0% {
            opacity: 0.3;
          }
          50% {
            opacity: 1;
          }
          100% {
            opacity: 0.3;
          }
        }

        @keyframes shimmerButton {
          0% {
            left: -100%;
          }
          100% {
            left: 200%;
          }
        }

        @keyframes kenBurns {
          0% {
            transform: scale(1) translate(0, 0);
          }
          100% {
            transform: scale(1.1) translate(-5%, -5%);
          }
        }

        @keyframes particle {
          0% {
            transform: translateY(0) translateX(0);
            opacity: 0;
          }
          10% {
            opacity: 1;
          }
          90% {
            opacity: 1;
          }
          100% {
            transform: translateY(-100vh) translateX(calc(50px - 100px * var(--random)));
            opacity: 0;
          }
        }

        input::-webkit-calendar-picker-indicator {
          filter: invert(1);
        }

        @media (max-width: 768px) {
          div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
          }
        }
      `}</style>
    </div>
  )
}

export default HeroSection