// src/components/home/HeroSection.tsx
import { useState } from 'react'
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

  // URL de l'API Laravel
  const API_URL = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api'

  // URL de l'image de fond (optionnelle)
  const backgroundImage = '/assets/doctor-reading.jpg'

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

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target
    setFormData(prev => ({
      ...prev,
      [name]: value
    }))
  }

  // Styles réutilisables
  const inputStyle = {
    width: '100%',
    padding: '0.75rem 1rem',
    marginBottom: '0.75rem',
    background: 'rgba(51, 65, 85, 0.5)',
    border: '1px solid rgba(34, 197, 94, 0.3)',
    borderRadius: '0.5rem',
    color: 'white',
    fontSize: '0.95rem',
    transition: 'all 0.3s ease',
    boxSizing: 'border-box' as const
  }

  const selectStyle = {
    ...inputStyle,
    cursor: 'pointer' as const
  }

  const handleInputFocus = (e: React.FocusEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    e.currentTarget.style.borderColor = '#22c55e'
    e.currentTarget.style.background = 'rgba(51, 65, 85, 0.8)'
  }

  const handleInputBlur = (e: React.FocusEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.3)'
    e.currentTarget.style.background = 'rgba(51, 65, 85, 0.5)'
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
      padding: '80px 20px 40px'
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
          animation: 'kenBurns 30s ease-in-out infinite alternate'
        }}></div>
      )}

      {/* Gradient overlay principal avec transparence */}
      <div style={{
        position: 'absolute',
        top: 0,
        left: 0,
        width: '100%',
        height: '100%',
        background: 'linear-gradient(135deg, rgba(33, 75, 165, 0.85) 0%, rgba(46, 55, 70, 0.90) 50%, rgba(6, 92, 42, 0.85) 100%)',
        zIndex: 1
      }}></div>

      {/* Particules animées */}
      <div className="particles-container" style={{
        position: 'absolute',
        top: 0,
        left: 0,
        width: '100%',
        height: '100%',
        zIndex: 2,
        pointerEvents: 'none'
      }}>
        {[...Array(8)].map((_, i) => (
          <div
            key={i}
            style={{
              position: 'absolute',
              width: '4px',
              height: '4px',
              background: 'rgba(34, 197, 94, 0.6)',
              borderRadius: '50%',
              top: `${Math.random() * 100}%`,
              left: `${Math.random() * 100}%`,
              animation: `particle ${5 + Math.random() * 5}s linear infinite`,
              animationDelay: `${Math.random() * 5}s`
            }}
          />
        ))}
      </div>

      {/* Orbes lumineux animés */}
      <div style={{
        position: 'absolute',
        top: '-10%',
        right: '-5%',
        width: '500px',
        height: '500px',
        background: 'radial-gradient(circle, rgba(34, 197, 94, 0.2) 0%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(60px)',
        pointerEvents: 'none',
        animation: 'float 8s ease-in-out infinite',
        zIndex: 2
      }}></div>
      <div style={{
        position: 'absolute',
        bottom: '-10%',
        left: '-5%',
        width: '500px',
        height: '500px',
        background: 'radial-gradient(circle, rgba(34, 197, 94, 0.15) 0%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(60px)',
        pointerEvents: 'none',
        animation: 'float 10s ease-in-out infinite 2s',
        zIndex: 2
      }}></div>
      <div style={{
        position: 'absolute',
        top: '50%',
        left: '50%',
        transform: 'translate(-50%, -50%)',
        width: '600px',
        height: '600px',
        background: 'radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%)',
        borderRadius: '50%',
        filter: 'blur(80px)',
        pointerEvents: 'none',
        animation: 'pulse 6s ease-in-out infinite',
        zIndex: 2
      }}></div>

      <div style={{
        maxWidth: '1400px',
        margin: '0 auto',
        width: '100%',
        position: 'relative',
        zIndex: 3
      }}>
        <div style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))',
          gap: '2rem',
          alignItems: 'center'
        }}>
          {/* Colonne Gauche - Contenu textuel */}
          <div style={{
            animation: 'slideInLeft 0.8s ease-out, fadeIn 0.8s ease-out'
          }}>
            <div>
              {/* Badge de disponibilité */}
              <div style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '0.5rem',
                padding: '0.5rem 1rem',
                background: 'rgba(34, 197, 94, 0.1)',
                border: '1px solid rgba(34, 197, 94, 0.3)',
                borderRadius: '2rem',
                marginBottom: '1.5rem',
                animation: 'slideInDown 0.8s ease-out, shimmer 3s ease-in-out infinite'
              }}>
                <span style={{
                  width: '8px',
                  height: '8px',
                  background: '#22c55e',
                  borderRadius: '50%',
                  animation: 'blink 2s ease-in-out infinite'
                }}></span>
                <span style={{
                  fontSize: 'clamp(0.75rem, 2vw, 0.9rem)',
                  color: '#22c55e',
                  fontWeight: 600
                }}>Disponible 24/7</span>
              </div>

              <h1
                style={{
                  fontSize: 'clamp(1.5rem, 8vw, 6rem)',
                  fontWeight: 'bold',
                  lineHeight: '1.2',
                  marginBottom: '1rem',
                  animation: 'slideInDown 0.8s ease-out 0.2s both',
                  color: '#22c55e'
                }}
              >
                CMO_
                <span style={{
                  background: 'linear-gradient(135deg, #FFFFFFFF 50%, #1075B9 50%)',
                  WebkitBackgroundClip: 'text',
                  WebkitTextFillColor: 'transparent',
                  backgroundClip: 'text',
                  fontWeight: 'black'
                }}>
                  VISTAMD
                </span>
              </h1>

              <p style={{
                fontSize: 'clamp(1rem, 4vw, 2.8rem)',
                color: '#e2e8f0',
                marginBottom: '1rem',
                fontWeight: 500,
                animation: 'slideInDown 0.8s ease-out 0.3s both'
              }}>
                Excellence médicale, compassion sans limite
              </p>

              <p style={{
                fontSize: 'clamp(0.85rem, 3vw, 1.8rem)',
                color: '#cbd5e1',
                marginBottom: '1.5rem',
                lineHeight: '1.6',
                maxWidth: '500px',
                animation: 'slideInDown 0.8s ease-out 0.4s both'
              }}>
                Soins immédiats et suivi à long terme, avec une équipe dédiée à votre bien-être.
Transport médicalisé 24/7, équipements de pointe, expertise médicale de qualité.
              </p>

              <div style={{
                display: 'flex',
                gap: '1rem',
                flexWrap: 'wrap',
                marginBottom: '2rem',
                animation: 'slideInUp 0.8s ease-out 0.5s both'
              }}>
                <a
                  href="tel:+2250700000000"
                  style={{
                    display: 'flex',
                    alignItems: 'center',
                    gap: '0.75rem',
                    padding: '0.75rem 1.25rem',
                    borderRadius: '0.5rem',
                    border: '2px solid rgba(34, 197, 94, 0.5)',
                    backgroundColor: 'rgba(34, 197, 94, 0.05)',
                    transition: 'all 0.3s ease',
                    cursor: 'pointer',
                    textDecoration: 'none',
                    color: 'white',
                    animation: 'pulse 2s ease-in-out infinite'
                  }}
                  onMouseEnter={(e: React.MouseEvent<HTMLAnchorElement>) => {
                    const target = e.currentTarget
                    target.style.borderColor = '#22c55e'
                    target.style.backgroundColor = 'rgba(34, 197, 94, 0.15)'
                    target.style.transform = 'translateY(-3px) scale(1.05)'
                    target.style.boxShadow = '0 10px 30px rgba(34, 197, 94, 0.4)'
                  }}
                  onMouseLeave={(e: React.MouseEvent<HTMLAnchorElement>) => {
                    const target = e.currentTarget
                    target.style.borderColor = 'rgba(34, 197, 94, 0.5)'
                    target.style.backgroundColor = 'rgba(34, 197, 94, 0.05)'
                    target.style.transform = 'translateY(0) scale(1)'
                    target.style.boxShadow = 'none'
                  }}
                >
                  <span style={{ fontSize: '1.2rem' }}>📞</span>
                  <div>
                    <div style={{ fontSize: 'clamp(0.7rem, 2vw, 0.8rem)', color: '#94a3b8' }}>Urgence 24/7</div>
                    <div style={{ fontSize: 'clamp(0.8rem, 2.5vw, 1rem)', fontWeight: 'bold', color: '#22c55e' }}>+225 07 205 520 99</div>
                  </div>
                </a>
              </div>
            </div>
          </div>

          {/* Colonne Droite - Formulaire de rendez-vous */}
          <div style={{
            animation: 'slideInRight 0.8s ease-out 0.2s both, floatSlow 6s ease-in-out infinite 1s'
          }}>
            <div style={{
              background: 'rgba(30, 41, 59, 0.8)',
              backdropFilter: 'blur(10px)',
              border: '1px solid rgba(34, 197, 94, 0.2)',
              borderRadius: '1rem',
              padding: 'clamp(1rem, 5vw, 2.5rem)',
              boxShadow: '0 20px 60px rgba(0, 0, 0, 0.3)',
              position: 'relative',
              overflow: 'hidden',
              transition: 'all 0.3s ease',
              animation: 'slideInUp 0.8s ease-out 0.3s both, glow 3s ease-in-out infinite'
            }}
            onMouseEnter={(e) => {
              e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.5)'
              e.currentTarget.style.transform = 'translateY(-5px)'
              e.currentTarget.style.boxShadow = '0 25px 70px rgba(34, 197, 94, 0.3)'
            }}
            onMouseLeave={(e) => {
              e.currentTarget.style.borderColor = 'rgba(34, 197, 94, 0.2)'
              e.currentTarget.style.transform = 'translateY(0)'
              e.currentTarget.style.boxShadow = '0 20px 60px rgba(0, 0, 0, 0.3)'
            }}>
              {/* Orbe décoratif rotatif */}
              <div style={{
                position: 'absolute',
                top: '-50%',
                right: '-50%',
                width: '300px',
                height: '300px',
                background: 'radial-gradient(circle, rgba(34, 197, 94, 0.15) 0%, transparent 70%)',
                borderRadius: '50%',
                pointerEvents: 'none',
                animation: 'rotate 6s linear infinite'
              }}></div>

              {/* Lignes décoratives animées */}
              <div style={{
                position: 'absolute',
                top: 0,
                left: 0,
                width: '100%',
                height: '2px',
                background: 'linear-gradient(90deg, transparent, #22c55e, transparent)',
                animation: 'shimmerLine 3s ease-in-out infinite'
              }}></div>
              <div style={{
                position: 'absolute',
                bottom: 0,
                right: 0,
                width: '2px',
                height: '100%',
                background: 'linear-gradient(180deg, transparent, #22c55e, transparent)',
                animation: 'shimmerLine 3s ease-in-out infinite 1.5s'
              }}></div>

              <div style={{ position: 'relative', zIndex: 1 }}>
                <h3 style={{
                  fontSize: 'clamp(1.2rem, 4vw, 3rem)',
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
                  color: '#D7DDE6FF',
                  marginBottom: '1rem',
                  fontSize: 'clamp(0.8rem, 2.5vw, 1.5rem)',
                  textAlign: 'center'
                }}>
                  Des soins immédiats et à long terme sont disponibles pour vous.
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
                      style={inputStyle}
                      onFocus={handleInputFocus}
                      onBlur={handleInputBlur}
                    />

                    <input
                      type="text"
                      name="last_name"
                      placeholder="Nom *"
                      value={formData.last_name}
                      onChange={handleChange}
                      required
                      disabled={isSubmitting}
                      style={inputStyle}
                      onFocus={handleInputFocus}
                      onBlur={handleInputBlur}
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
                    style={inputStyle}
                    onFocus={handleInputFocus}
                    onBlur={handleInputBlur}
                  />

                  <input
                    type="tel"
                    name="phone_number"
                    placeholder="Votre Téléphone *"
                    value={formData.phone_number}
                    onChange={handleChange}
                    required
                    disabled={isSubmitting}
                    style={inputStyle}
                    onFocus={handleInputFocus}
                    onBlur={handleInputBlur}
                  />

                  <select
                    name="service_type"
                    value={formData.service_type}
                    onChange={handleChange}
                    required
                    disabled={isSubmitting}
                    style={selectStyle}
                  >
                    <option value="">Type de service *</option>
                    <option value="appointment">Rendez-vous médical</option>
                    <option value="home_visit">Visite à domicile</option>
                    <option value="emergency">Urgence</option>
                    <option value="transport">Transport médicalisé</option>
                    <option value="consultation">Consultation</option>
                    <option value="other">Autre service</option>
                  </select>

                  <textarea
                    name="message"
                    placeholder="Quelle est votre préoccupation? (facultatif)"
                    value={formData.message}
                    onChange={handleChange}
                    disabled={isSubmitting}
                    rows={3}
                    style={{ ...inputStyle, fontFamily: 'inherit', resize: 'vertical' }}
                    onFocus={handleInputFocus}
                    onBlur={handleInputBlur}
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
                      style={inputStyle}
                    />

                    <select
                      name="urgency"
                      value={formData.urgency}
                      onChange={handleChange}
                      disabled={isSubmitting}
                      style={selectStyle}
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
                    style={{ ...selectStyle, marginBottom: '1rem' }}
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
                      fontSize: '1rem',
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
                  fontSize: '1rem',
                  color: '#64748b',
                  textAlign: 'center',
                  marginTop: '0.75rem'
                }}>
                  Vos données sont sécurisées • Réponse sous 24h
                </p>
              </div>
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
